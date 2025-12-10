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
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para crear resultados de aprendizaje.');
        }
        
        return view('learning_outcomes.create', compact('competencia'));
    }

    public function store(Request $request, Competencia $competencia)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para crear resultados de aprendizaje.');
        }
        
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'unique:learning_outcomes,codigo', 'regex:/^[0-9]+$/'],
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ], [
            'codigo.regex' => 'El código solo puede contener números.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
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
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para editar resultados de aprendizaje.');
        }
        
        return view('learning_outcomes.edit', compact('learningOutcome'));
    }

    public function update(Request $request, LearningOutcome $learningOutcome)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para actualizar resultados de aprendizaje.');
        }
        
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'unique:learning_outcomes,codigo,' . $learningOutcome->id, 'regex:/^[0-9]+$/'],
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ], [
            'codigo.regex' => 'El código solo puede contener números.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        ]);

        $learningOutcome->update($validated);

        return redirect()->route('competencias.learning_outcomes.index', $learningOutcome->competencia)
            ->with('success', 'Resultado de Aprendizaje actualizado correctamente.');
    }

    public function destroy(LearningOutcome $learningOutcome)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para eliminar resultados de aprendizaje.');
        }
        
        if ($learningOutcome->studentLearningOutcomes()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un RA con calificaciones registradas.');
        }

        $competencia = $learningOutcome->competencia;
        $learningOutcome->delete();

        return redirect()->route('competencias.learning_outcomes.index', $competencia)
            ->with('success', 'Resultado de Aprendizaje eliminado correctamente.');
    }
}
