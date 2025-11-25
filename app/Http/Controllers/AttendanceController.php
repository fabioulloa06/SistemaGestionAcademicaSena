<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Verificar permisos
        if (!$user->canManageAttendance()) {
            abort(403, 'No tienes permiso para gestionar asistencias.');
        }
        
        $groupIds = $user->getAccessibleGroupIds();
        $query = \App\Models\Attendance_list::with(['student.group.program', 'instructor', 'competencia']);
        
        // Filtrar por grupos accesibles
        if ($user->isInstructor() || $user->isStudent()) {
            $query->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            });
        }

        // Filtros
        if ($request->has('fecha') && $request->fecha != '') {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->has('group_id') && $request->group_id != '' && ($groupIds->contains($request->group_id) || $user->canManageAcademicStructure())) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('group_id', $request->group_id);
            });
        }

        $attendances = $query->latest('fecha')->latest('id')->paginate(20);
        
        // Filtrar grupos según rol
        $groupsQuery = \App\Models\Group::with('program')->where('activo', true);
        if ($user->isInstructor() || $user->isStudent()) {
            $groupsQuery->whereIn('id', $groupIds);
        }
        $groups = $groupsQuery->orderBy('numero_ficha')->get();

        return view('attendance_lists.index', compact('attendances', 'groups'));
    }

    public function bulkCreate(Request $request)
    {
        $user = auth()->user();
        
        // Verificar permiso para gestionar asistencias
        if (!$user->canManageAttendance()) {
            abort(403, 'No tienes permiso para gestionar asistencias.');
        }
        
        // Filtrar grupos según rol (optimizado)
        $groupIds = $user->getAccessibleGroupIds();
        $groupsQuery = \App\Models\Group::with('program')->where('activo', true);
        if ($user->isInstructor()) {
            $groupsQuery->whereIn('id', $groupIds);
        }
        $groups = $groupsQuery->orderBy('numero_ficha')->get();
        
        $instructors = \App\Models\Instructor::where('activo', true)->get();
        $students = collect();
        $competencias = collect();

        if ($request->has('group_id') && $request->group_id != '') {
            // Verificar que tenga acceso a este grupo
            if (!$user->canManageAttendanceForGroup($request->group_id)) {
                abort(403, 'No tienes permiso para tomar asistencia en este grupo.');
            }
            
            $group = \App\Models\Group::find($request->group_id);
            $students = \App\Models\Student::where('group_id', $request->group_id)
                ->where('activo', true)
                ->orderBy('nombre')
                ->get();
            
            // Obtener competencias del programa
            if ($group) {
                $competencias = $group->program->competencias()->where('activo', true)->get();
            }
        }

        return view('attendance_lists.create', compact('groups', 'students', 'instructors', 'competencias'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'instructor_id' => 'required|exists:instructors,id',
            'competencia_id' => 'nullable|exists:competencias,id',
            'fecha' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.estado' => 'required|in:presente,ausente,tarde,justificado',
        ]);

        $warnings = [];

        foreach ($validated['attendances'] as $studentId => $data) {
            \App\Models\Attendance_list::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'fecha' => $validated['fecha'],
                    'competencia_id' => $validated['competencia_id'] ?? null,
                ],
                [
                    'group_id' => $validated['group_id'],
                    'instructor_id' => $validated['instructor_id'],
                    'estado' => $data['estado'],
                    'observaciones' => $data['observaciones'] ?? null,
                ]
            );

            // VERIFICACIÓN DE LÍMITES (NOTIFICACIONES)
            if ($data['estado'] === 'ausente') {
                $student = \App\Models\Student::find($studentId);
                
                if ($student) {
                    // Validación 1: Total de ausencias a la misma competencia
                    if ($validated['competencia_id']) {
                        // Usar Attendance_list para contar
                        $absencesCount = \App\Models\Attendance_list::where('student_id', $studentId)
                            ->where('competencia_id', $validated['competencia_id'])
                            ->where('estado', 'ausente')
                            ->count();

                        if ($absencesCount >= 3) {
                            $competencia = \App\Models\Competencia::find($validated['competencia_id']);
                            $warnings[] = "🚨 {$student->nombre} ha faltado {$absencesCount} veces a {$competencia->nombre}";
                            
                            // Enviar Correo
                            if ($student->user) {
                                \Illuminate\Support\Facades\Mail::to($student->user->email)->send(new \App\Mail\AttendanceWarning($student, ['total' => $absencesCount, 'consecutive' => 'N/A']));
                            }
                        } elseif ($absencesCount == 2) {
                            $competencia = \App\Models\Competencia::find($validated['competencia_id']);
                            $warnings[] = "⚠️ {$student->nombre} tiene 2 ausencias a {$competencia->nombre} (una más y alcanza el límite)";
                        }
                        
                        // Validación 2: Ausencias consecutivas a la misma competencia
                        // Simplificado: verificar si las últimas 3 asistencias a esta competencia son 'ausente'
                        $lastThree = \App\Models\Attendance_list::where('student_id', $studentId)
                            ->where('competencia_id', $validated['competencia_id'])
                            ->orderBy('fecha', 'desc')
                            ->take(3)
                            ->get();
                        
                        if ($lastThree->count() >= 3 && $lastThree->every(fn($att) => $att->estado === 'ausente')) {
                            $competencia = \App\Models\Competencia::find($validated['competencia_id']);
                            $warnings[] = "🚨 {$student->nombre} tiene 3 AUSENCIAS CONSECUTIVAS a {$competencia->nombre}";
                            
                            // Enviar Correo
                            if ($student->user) {
                                \Illuminate\Support\Facades\Mail::to($student->user->email)->send(new \App\Mail\AttendanceWarning($student, ['total' => 'N/A', 'consecutive' => 3]));
                            }
                        } elseif ($lastThree->count() >= 2 && $lastThree->take(2)->every(fn($att) => $att->estado === 'ausente')) {
                            // Advertencia temprana: 2 ausencias consecutivas
                            $competencia = \App\Models\Competencia::find($validated['competencia_id']);
                            $warnings[] = "⚠️ {$student->nombre} tiene 2 AUSENCIAS CONSECUTIVAS a {$competencia->nombre} (una más y alcanza el límite crítico)";
                            
                            // Enviar Correo de Advertencia
                            if ($student->user) {
                                \Illuminate\Support\Facades\Mail::to($student->user->email)->send(new \App\Mail\AttendanceWarning($student, ['total' => 'N/A', 'consecutive' => 2]));
                            }
                        }
                    }
                    
                    // Validación 3: Ausencias consecutivas en días calendario (General)
                    $lastThreeGeneral = \App\Models\Attendance_list::where('student_id', $studentId)
                        ->orderBy('fecha', 'desc')
                        ->take(3)
                        ->get();

                    if ($lastThreeGeneral->count() >= 3 && $lastThreeGeneral->every(fn($att) => $att->estado === 'ausente')) {
                        $warnings[] = "🚨 {$student->nombre} tiene 3 AUSENCIAS CONSECUTIVAS EN GENERAL";
                    }
                }
            }
        }

        $message = 'Asistencia registrada correctamente.';
        if (!empty($warnings)) {
            // Eliminar duplicados
            $warnings = array_unique($warnings);
            return redirect()->route('attendance.index')->with('warning', implode('<br>', $warnings));
        }

        return redirect()->route('attendance.index')->with('success', $message);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('attendance.bulk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not used for single creation in this flow
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
