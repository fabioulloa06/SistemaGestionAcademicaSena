<?php

namespace App\Http\Controllers;

use App\Models\ImprovementPlan;
use App\Models\DisciplinaryAction;
use App\Models\Instructor;
use Illuminate\Http\Request;

class ImprovementPlanController extends Controller
{
    public function index()
    {
        $plans = ImprovementPlan::with('disciplinaryAction.student', 'instructor')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('improvement_plans.index', compact('plans'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        
        // Coordinador solo puede ver, no crear
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para crear planes de mejoramiento. Tu rol es de revisión y vigilancia.');
        }
        
        $disciplinaryActionId = $request->query('disciplinary_action');
        $disciplinaryAction = null;
        $student = null;
        $type = 'Académico';

        if ($disciplinaryActionId) {
            $disciplinaryAction = DisciplinaryAction::findOrFail($disciplinaryActionId);
            $student = $disciplinaryAction->student;
            $type = $disciplinaryAction->tipo_falta === 'Académica' ? 'Académico' : 'Disciplinario';
        }

        $instructors = Instructor::where('activo', true)->get();
        return view('improvement_plans.create', compact('disciplinaryAction', 'instructors', 'student', 'type'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Coordinador solo puede ver, no crear
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para crear planes de mejoramiento. Tu rol es de revisión y vigilancia.');
        }
        
        $validated = $request->validate([
            'disciplinary_action_id' => 'nullable|exists:disciplinary_actions,id',
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:Académico,Disciplinario',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'instructor_id' => 'required|exists:instructors,id',
            'observations' => 'nullable|string',
        ]);

        ImprovementPlan::create($validated);

        return redirect()->route('improvement_plans.index')
            ->with('success', 'Plan de mejoramiento creado correctamente.');
    }

    public function print(ImprovementPlan $improvementPlan)
    {
        return view('improvement_plans.print', compact('improvementPlan'));
    }

    public function show(ImprovementPlan $improvementPlan)
    {
        $improvementPlan->load('disciplinaryAction.student', 'instructor');
        return view('improvement_plans.show', compact('improvementPlan'));
    }

    public function edit(ImprovementPlan $improvementPlan)
    {
        $user = auth()->user();
        
        // Coordinador solo puede ver, no editar
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para editar planes de mejoramiento. Tu rol es de revisión y vigilancia.');
        }
        
        $instructors = Instructor::where('activo', true)->get();
        return view('improvement_plans.edit', compact('improvementPlan', 'instructors'));
    }

    public function update(Request $request, ImprovementPlan $improvementPlan)
    {
        $user = auth()->user();
        
        // Coordinador solo puede ver, no actualizar
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para actualizar planes de mejoramiento. Tu rol es de revisión y vigilancia.');
        }
        
        $validated = $request->validate([
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:Pendiente,En Progreso,Cumplido,Incumplido',
            'instructor_id' => 'required|exists:instructors,id',
            'observations' => 'nullable|string',
        ]);

        $improvementPlan->update($validated);

        return redirect()->route('improvement_plans.index')
            ->with('success', 'Plan de mejoramiento actualizado correctamente.');
    }

    public function destroy(ImprovementPlan $improvementPlan)
    {
        $user = auth()->user();
        
        // Coordinador solo puede ver, no eliminar
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para eliminar planes de mejoramiento. Tu rol es de revisión y vigilancia.');
        }
        
        $improvementPlan->delete();

        return redirect()->route('improvement_plans.index')
            ->with('success', 'Plan de mejoramiento eliminado correctamente.');
    }
}
