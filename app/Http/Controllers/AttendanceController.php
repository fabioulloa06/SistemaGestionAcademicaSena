<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'No autenticado.');
        }
        
        // Verificar permisos - Coordinador puede ver pero no gestionar
        if (!$user->canViewAttendance()) {
            abort(403, 'No tienes permiso para ver inasistencias.');
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
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'No autenticado.');
        }
        
        // Coordinador solo puede ver, no crear
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para registrar inasistencias. Tu rol es de revisión y vigilancia.');
        }
        
        // Verificar permiso para gestionar asistencias
        if (!$user->canManageAttendance()) {
            abort(403, 'No tienes permiso para gestionar inasistencias.');
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
                abort(403, 'No tienes permiso para registrar inasistencias en este grupo.');
            }
            
            $group = \App\Models\Group::find($request->group_id);
            $students = \App\Models\Student::where('group_id', $request->group_id)
                ->where('activo', true)
                ->orderBy('nombre')
                ->get();
            
            // Obtener competencias del programa
            // Si es instructor líder, ve todas las competencias de su ficha
            // Si es otro instructor, solo ve las competencias a las que está asignado
            if ($group) {
                if ($user->isInstructor()) {
                    // Verificar si es instructor líder de esta ficha
                    if ($user->isInstructorLiderOfGroup($request->group_id)) {
                        // Instructor líder ve todas las competencias de su ficha
                        $competencias = $group->program->competencias()->where('activo', true)->get();
                    } else {
                        // Otros instructores solo ven las competencias asignadas
                        $competenciasAsignadas = \App\Models\CompetenciaGroupInstructor::where('group_id', $request->group_id)
                            ->where('instructor_id', $user->id)
                            ->pluck('competencia_id');
                        
                        // Buscar competencias por id_competencia o id
                        $competencias = \App\Models\Competencia::where(function($query) use ($competenciasAsignadas) {
                            $query->whereIn('id_competencia', $competenciasAsignadas)
                                  ->orWhereIn('id', $competenciasAsignadas);
                        })->where('activo', true)->get();
                    }
                } else {
                    // Admin y coordinador ven todas las competencias
                    $competencias = $group->program->competencias()->where('activo', true)->get();
                }
            }
        }

        $learningOutcomes = collect();
        if ($request->has('competencia_id') && $request->competencia_id != '') {
            $competencia = \App\Models\Competencia::find($request->competencia_id);
            if ($competencia) {
                // Obtener resultados de aprendizaje de la competencia
                $learningOutcomes = \App\Models\LearningOutcome::where('competencia_id', $competencia->id_competencia ?? $competencia->id)
                    ->where('activo', true)
                    ->get();
            }
        }

        return view('attendance_lists.create', compact('groups', 'students', 'instructors', 'competencias', 'learningOutcomes'));
    }

    public function bulkStore(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'No autenticado.');
        }
        
        // Coordinador solo puede ver, no crear
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para registrar inasistencias. Tu rol es de revisión y vigilancia.');
        }
        
        if (!$user->canManageAttendance()) {
            abort(403, 'No tienes permiso para registrar inasistencias.');
        }
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'instructor_id' => 'required|exists:instructors,id',
            'competencia_id' => 'required|exists:competencias,id',
            'learning_outcome_id' => 'nullable|exists:learning_outcomes,id',
            'fecha' => 'required|date',
            'inasistencias' => 'required|array',
            'inasistencias.*' => 'nullable|boolean',
            'inasistencias.*.observaciones' => 'nullable|string|max:500',
        ]);

        $warnings = [];
        $group = \App\Models\Group::with(['program', 'instructorLider'])->find($validated['group_id']);

        // Obtener instructor líder del grupo (asignado directamente a la ficha)
        $instructorLider = $group->instructorLider;

        // Obtener instructor asignado a esta competencia específica en este grupo
        $instructorCompetencia = \App\Models\CompetenciaGroupInstructor::where('group_id', $validated['group_id'])
            ->where('competencia_id', $validated['competencia_id'])
            ->with('instructor')
            ->first()
            ?->instructor;

        foreach ($validated['inasistencias'] as $studentId => $data) {
            // Solo registrar si hay inasistencia marcada
            if (!isset($data) || !$data) {
                continue;
            }

            $student = \App\Models\Student::find($studentId);
            if (!$student) {
                continue;
            }

            // Registrar la inasistencia
            \App\Models\Attendance_list::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'fecha' => $validated['fecha'],
                    'competencia_id' => $validated['competencia_id'],
                ],
                [
                    'group_id' => $validated['group_id'],
                    'instructor_id' => $validated['instructor_id'],
                    'learning_outcome_id' => $validated['learning_outcome_id'] ?? null,
                    'estado' => 'ausente',
                    'observaciones' => $data['observaciones'] ?? null,
                ]
            );

            // VERIFICACIÓN DE LÍMITES SEGÚN NUEVA NORMA SENA
            // ALERTAS TEMPRANAS: Se notifica ANTES de alcanzar los límites para prevención
            // Límites permitidos: 3 días consecutivos o 5 inasistencias totales
            // Alertas: 2 días consecutivos (antes de llegar a 3) o 4 totales (antes de llegar a 5)
            
            $competencia = \App\Models\Competencia::find($validated['competencia_id']);
            
            // 1. Verificar días consecutivos con inasistencias A LA MISMA COMPETENCIA
            $consecutiveAbsencesCompetencia = $this->checkConsecutiveAbsencesToCompetencia($studentId, $validated['competencia_id'], 2);
            
            // 2. Verificar inasistencias totales A LA MISMA COMPETENCIA
            $totalAbsencesCompetencia = \App\Models\Attendance_list::where('student_id', $studentId)
                ->where('competencia_id', $validated['competencia_id'])
                ->where('estado', 'ausente')
                ->count();

            // 3. Verificar inasistencias a resultado de aprendizaje específico (si se especificó)
            $totalAbsencesRA = 0;
            if ($validated['learning_outcome_id']) {
                $totalAbsencesRA = \App\Models\Attendance_list::where('student_id', $studentId)
                    ->where('learning_outcome_id', $validated['learning_outcome_id'])
                    ->where('estado', 'ausente')
                    ->count();
            }

            $shouldNotify = false;
            $reason = null;
            $details = [];

            // ALERTA TEMPRANA: Notificar cuando tenga 2 días consecutivos (antes de llegar a 3)
            if ($consecutiveAbsencesCompetencia >= 2 && $consecutiveAbsencesCompetencia < 3) {
                $shouldNotify = true;
                $reason = 'consecutive_warning';
                $details = [
                    'consecutive' => $consecutiveAbsencesCompetencia,
                    'limit' => 3,
                    'competencia' => $competencia->nombre_competencia ?? $competencia->nombre ?? 'Competencia',
                ];
                $warnings[] = "⚠️ ALERTA TEMPRANA: {$student->nombre} tiene {$consecutiveAbsencesCompetencia} días CONSECUTIVOS con inasistencias a {$details['competencia']} (Límite permitido: 3 días)";
            }

            // ALERTA TEMPRANA: Notificar cuando tenga 4 inasistencias totales (antes de llegar a 5)
            if ($totalAbsencesCompetencia >= 4 && $totalAbsencesCompetencia < 5) {
                $shouldNotify = true;
                $reason = 'total_warning';
                $details = [
                    'total' => $totalAbsencesCompetencia,
                    'limit' => 5,
                    'competencia' => $competencia->nombre_competencia ?? $competencia->nombre ?? 'Competencia',
                ];
                $warnings[] = "⚠️ ALERTA TEMPRANA: {$student->nombre} tiene {$totalAbsencesCompetencia} inasistencias a {$details['competencia']} (Límite permitido: 5 inasistencias)";
            }

            // ALERTA CRÍTICA: Si ya alcanzó o superó los límites
            if ($consecutiveAbsencesCompetencia >= 3) {
                $shouldNotify = true;
                $reason = 'consecutive_limit';
                $details = [
                    'consecutive' => $consecutiveAbsencesCompetencia,
                    'limit' => 3,
                    'competencia' => $competencia->nombre_competencia ?? $competencia->nombre ?? 'Competencia',
                ];
                $warnings[] = "🚨 CRÍTICO: {$student->nombre} ha alcanzado {$consecutiveAbsencesCompetencia} días CONSECUTIVOS con inasistencias a {$details['competencia']} (Límite: 3 días)";
            }

            if ($totalAbsencesCompetencia >= 5) {
                $shouldNotify = true;
                $reason = 'total_limit';
                $details = [
                    'total' => $totalAbsencesCompetencia,
                    'limit' => 5,
                    'competencia' => $competencia->nombre_competencia ?? $competencia->nombre ?? 'Competencia',
                ];
                $warnings[] = "🚨 CRÍTICO: {$student->nombre} ha alcanzado {$totalAbsencesCompetencia} inasistencias a {$details['competencia']} (Límite: 5 inasistencias)";
            }

            // Advertencia adicional si hay muchas inasistencias a un resultado de aprendizaje específico
            if ($validated['learning_outcome_id'] && $totalAbsencesRA >= 2) {
                $learningOutcome = \App\Models\LearningOutcome::find($validated['learning_outcome_id']);
                $warnings[] = "⚠️ {$student->nombre} tiene {$totalAbsencesRA} inasistencias al Resultado de Aprendizaje: {$learningOutcome->nombre}";
            }

            // Enviar notificaciones si se alcanzó alguno de los límites
            if ($shouldNotify) {
                // Notificar al estudiante
                if ($student->user && $student->user->email) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($student->user->email)
                            ->send(new \App\Mail\MatriculaCancellationWarning($student, $reason, $details));
                    } catch (\Exception $e) {
                        Log::error("Error enviando correo a estudiante: " . $e->getMessage());
                    }
                }

                // Obtener todos los instructores asignados a competencias del programa del estudiante
                $programInstructors = $this->getProgramInstructors($student->group_id);
                
                // Notificar al instructor líder (siempre recibe las alertas)
                if ($instructorLider && $instructorLider->email) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($instructorLider->email)
                            ->send(new \App\Mail\InstructorLeaderNotification($student, $reason, $details, $group, $competencia));
                    } catch (\Exception $e) {
                        Log::error("Error enviando correo a instructor líder: " . $e->getMessage());
                    }
                }

                // Notificar a todos los instructores del programa (incluyendo el de la competencia específica)
                $notifiedInstructors = collect();
                if ($instructorLider) {
                    $notifiedInstructors->push($instructorLider->id);
                }

                foreach ($programInstructors as $instructor) {
                    // Evitar duplicados (no notificar dos veces al mismo instructor)
                    if ($notifiedInstructors->contains($instructor->id)) {
                        continue;
                    }

                    if ($instructor->email) {
                        try {
                            \Illuminate\Support\Facades\Mail::to($instructor->email)
                                ->send(new \App\Mail\InstructorLeaderNotification($student, $reason, $details, $group, $competencia, $instructor));
                            $notifiedInstructors->push($instructor->id);
                        } catch (\Exception $e) {
                            Log::error("Error enviando correo a instructor del programa ({$instructor->email}): " . $e->getMessage());
                        }
                    }
                }
            }
        }

        $message = 'Inasistencias registradas correctamente.';
        if (!empty($warnings)) {
            // Eliminar duplicados
            $warnings = array_unique($warnings);
            return redirect()->route('attendance-lists.index')->with('warning', implode('<br>', $warnings));
        }

        return redirect()->route('attendance-lists.index')->with('success', $message);
    }

    /**
     * Obtener todos los instructores asignados a competencias del programa del estudiante
     * Esto incluye instructores que están dando resultados de aprendizaje de cualquier competencia del programa
     */
    private function getProgramInstructors($groupId)
    {
        $group = \App\Models\Group::with('program.competencias')->find($groupId);
        
        if (!$group || !$group->program) {
            return collect();
        }

        // Obtener todas las competencias del programa
        $competenciaIds = $group->program->competencias->pluck('id');

        // Obtener todos los instructores asignados a competencias de este grupo
        $instructors = \App\Models\CompetenciaGroupInstructor::where('group_id', $groupId)
            ->whereIn('competencia_id', $competenciaIds)
            ->with('instructor')
            ->get()
            ->pluck('instructor')
            ->filter()
            ->unique('id');

        return $instructors;
    }

    /**
     * Verificar inasistencias consecutivas a una competencia específica
     * Retorna el número máximo de días consecutivos con inasistencias a esa competencia
     */
    private function checkConsecutiveAbsencesToCompetencia($studentId, $competenciaId, $minDays = 3): int
    {
        $absences = \App\Models\Attendance_list::where('student_id', $studentId)
            ->where('competencia_id', $competenciaId)
            ->where('estado', 'ausente')
            ->orderBy('fecha', 'desc')
            ->get();

        if ($absences->count() < $minDays) {
            return 0;
        }

        // Obtener fechas únicas y ordenarlas de más reciente a más antigua
        $dates = $absences->pluck('fecha')->map(function($date) {
            return is_string($date) ? \Carbon\Carbon::parse($date)->startOfDay() : $date->startOfDay();
        })->unique()->sort()->reverse()->values();

        if ($dates->count() < $minDays) {
            return 0;
        }

        // Verificar la secuencia más reciente de días consecutivos
        $maxConsecutive = 1;
        $currentConsecutive = 1;

        // Comenzar desde la fecha más reciente
        for ($i = 0; $i < $dates->count() - 1; $i++) {
            $currentDate = $dates[$i];
            $nextDate = $dates[$i + 1];
            
            // Calcular diferencia en días
            $diff = $currentDate->diffInDays($nextDate);
            
            if ($diff == 1) {
                // Días consecutivos
                $currentConsecutive++;
            } else {
                // Rompe la secuencia - verificar si esta es la secuencia más larga
                $maxConsecutive = max($maxConsecutive, $currentConsecutive);
                $currentConsecutive = 1;
            }
        }

        // Verificar la última secuencia
        $maxConsecutive = max($maxConsecutive, $currentConsecutive);
        
        return $maxConsecutive;
    }

    /**
     * Obtener resultados de aprendizaje de una competencia (AJAX)
     */
    public function getLearningOutcomes(Request $request)
    {
        $competenciaId = $request->query('competencia_id');
        
        if (!$competenciaId) {
            return response()->json([]);
        }
        
        // Buscar learning outcomes por competencia_id
        // Competencia puede usar id_competencia o id como primary key
        $learningOutcomes = \App\Models\LearningOutcome::where('competencia_id', $competenciaId)
            ->where('activo', true)
            ->select('id', 'codigo', 'nombre')
            ->get();
        
        return response()->json($learningOutcomes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('attendance-lists.create');
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
