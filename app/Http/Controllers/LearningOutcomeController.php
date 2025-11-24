<?php

namespace App\Http\Controllers;

use App\Models\LearningOutcome;
use App\Models\Competencia;
use Illuminate\Http\Request;

class LearningOutcomeController extends Controller
{
    public function index(Competencia $competencia)
    {
        $learningOutcomes = $competencia->learningOutcomes()->paginate(10);
        return view('learning_outcomes.index', compact('competencia', 'learningOutcomes'));
    }

    public function create(Competencia $competencia)
    {
        return view('learning_outcomes.create', compact('competencia'));
    }

    public function store(Request $request, Competencia $competencia)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|unique:learning_outcomes,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $competencia->learningOutcomes()->create($validated);

        return redirect()->route('competencias.learning_outcomes.index', $competencia)
            ->with('success', 'Resultado de Aprendizaje creado correctamente.');
    }

    public function show(LearningOutcome $learningOutcome)
    {
        return view('learning_outcomes.show', compact('learningOutcome'));
    }

    public function edit(LearningOutcome $learningOutcome)
    {
        return view('learning_outcomes.edit', compact('learningOutcome'));
    }

    public function update(Request $request, LearningOutcome $learningOutcome)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|unique:learning_outcomes,codigo,' . $learningOutcome->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $learningOutcome->update($validated);

        return redirect()->route('competencias.learning_outcomes.index', $learningOutcome->competencia)
            ->with('success', 'Resultado de Aprendizaje actualizado correctamente.');
    }

    public function destroy(LearningOutcome $learningOutcome)
    {
        if ($learningOutcome->studentLearningOutcomes()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un RA con calificaciones registradas.');
        }

        $competencia = $learningOutcome->competencia;
        $learningOutcome->delete();

        return redirect()->route('competencias.learning_outcomes.index', $competencia)
            ->with('success', 'Resultado de Aprendizaje eliminado correctamente.');
    }
}
