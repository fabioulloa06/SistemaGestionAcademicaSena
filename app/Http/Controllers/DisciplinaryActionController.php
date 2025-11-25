<?php

namespace App\Http\Controllers;

use App\Models\DisciplinaryAction;
use App\Models\Student;
use Illuminate\Http\Request;

class DisciplinaryActionController extends Controller
{
    public function index(Student $student)
    {
        $actions = $student->disciplinary_actions()->orderBy('date', 'desc')->paginate(10);
        return view('disciplinary_actions.index', compact('student', 'actions'));
    }

    public function globalIndex()
    {
        $user = auth()->user();
        
        // Verificar permisos
        if (!$user->canCreateDisciplinaryActions()) {
            abort(403, 'No tienes permiso para ver llamados de atención.');
        }
        
        $groupIds = $user->getAccessibleGroupIds();
        $query = DisciplinaryAction::with(['student.group.program', 'disciplinaryFault', 'academicFault']);
        
        // Filtrar por grupos accesibles
        if ($user->isInstructor() || $user->isStudent()) {
            $query->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            });
        }
        
        $actions = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(15);
        return view('disciplinary_actions.global_index', compact('actions'));
    }

    public function create(Student $student)
    {
        $user = auth()->user();
        
        // Verificar permiso para crear llamados de atención
        if (!$user->canCreateDisciplinaryActions()) {
            abort(403, 'No tienes permiso para crear llamados de atención.');
        }
        
        // Verificar que tenga acceso a este estudiante
        if (!$user->canCreateDisciplinaryActionForStudent($student->id)) {
            abort(403, 'No tienes permiso para crear llamados de atención para este estudiante.');
        }
        
        $faults = \App\Models\DisciplinaryFault::all();
        $academicFaults = \App\Models\AcademicFault::all();
        
        $academicCalls = $student->disciplinary_actions()
            ->where('tipo_falta', 'Académica')
            ->where('tipo_llamado', 'written')
            ->count();
            
        $disciplinaryCalls = $student->disciplinary_actions()
            ->where('tipo_falta', 'Disciplinaria')
            ->where('tipo_llamado', 'written')
            ->count();

        return view('disciplinary_actions.create', compact('student', 'faults', 'academicFaults', 'academicCalls', 'disciplinaryCalls'));
    }

    public function store(Request $request, Student $student)
    {
        $user = auth()->user();
        
        // Verificar permiso para crear llamados de atención
        if (!$user->canCreateDisciplinaryActions()) {
            abort(403, 'No tienes permiso para crear llamados de atención.');
        }
        
        // Verificar que tenga acceso a este estudiante
        if (!$user->canCreateDisciplinaryActionForStudent($student->id)) {
            abort(403, 'No tienes permiso para crear llamados de atención para este estudiante.');
        }
        
        $validated = $request->validate([
            'tipo_falta' => 'required|in:Académica,Disciplinaria',
            'tipo_llamado' => 'required|in:verbal,written',
            'gravedad' => 'required|in:Leve,Grave,Gravísima',
            'description' => 'required|string',
            'date' => 'required|date',
            'disciplinary_fault_id' => 'nullable|exists:disciplinary_faults,id',
            'orientations_or_recommendations' => 'nullable|string',
        ]);

        // Validaciones SENA
        if ($validated['tipo_llamado'] === 'written') {
            $existingCalls = $student->disciplinary_actions()
                ->where('tipo_falta', $validated['tipo_falta'])
                ->where('tipo_llamado', 'written')
                ->count();

            if ($existingCalls >= 2) {
                return back()->withErrors(['error' => 'El aprendiz ya tiene 2 llamados de atención de este tipo. Debe crear un Plan de Mejoramiento.']);
            }

            if ($existingCalls == 1 && empty($validated['orientations_or_recommendations'])) {
                return back()->withErrors(['orientations_or_recommendations' => 'Para el segundo llamado es OBLIGATORIO incluir orientaciones/recomendaciones.']);
            }
        }

        if ($validated['tipo_falta'] === 'Disciplinaria' && empty($validated['disciplinary_fault_id'])) {
             return back()->withErrors(['disciplinary_fault_id' => 'Para faltas disciplinarias debe seleccionar el Literal infringido.']);
        }

        $action = $student->disciplinary_actions()->create($validated);

        // Enviar Correo
        if ($student->user) {
            \Illuminate\Support\Facades\Mail::to($student->user->email)->send(new \App\Mail\DisciplinarySanction($student, $action));
        }

        return redirect()->route('students.disciplinary_actions.index', $student)
            ->with('success', 'Llamado de atención registrado correctamente.');
    }

    public function edit(DisciplinaryAction $disciplinaryAction)
    {
        //
    }

    public function update(Request $request, DisciplinaryAction $disciplinaryAction)
    {
        //
    }

    public function destroy(DisciplinaryAction $disciplinaryAction)
    {
        $student = $disciplinaryAction->student;
        $disciplinaryAction->delete();
        return redirect()->route('students.disciplinary_actions.index', $student)
            ->with('success', 'Llamado de atención eliminado.');
    }

    public function print(DisciplinaryAction $disciplinaryAction)
    {
        return view('disciplinary_actions.print', compact('disciplinaryAction'));
    }
}
