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
        $instructors = Instructor::where('activo', true)->get();
        return view('improvement_plans.edit', compact('improvementPlan', 'instructors'));
    }

    public function update(Request $request, ImprovementPlan $improvementPlan)
    {
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
        $improvementPlan->delete();

        return redirect()->route('improvement_plans.index')
            ->with('success', 'Plan de mejoramiento eliminado correctamente.');
    }
}
