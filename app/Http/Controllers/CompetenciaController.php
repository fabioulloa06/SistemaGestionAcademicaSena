<?php

namespace App\Http\Controllers;

use App\Models\Competencia;
use App\Models\Program;
use Illuminate\Http\Request;

class CompetenciaController extends Controller
{
    public function index(Program $program)
    {
        $competencias = $program->competencias()->paginate(10);
        return view('competencias.index', compact('program', 'competencias'));
    }

    public function create(Program $program)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para crear competencias.');
        }
        
        return view('competencias.create', compact('program'));
    }

    public function store(Request $request, Program $program)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para crear competencias.');
        }
        
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'unique:competencias,codigo', 'regex:/^[0-9]+$/'],
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ], [
            'codigo.regex' => 'El código solo puede contener números.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        ]);

        $program->competencias()->create($validated);

        return redirect()->route('programs.competencias.index', $program)
            ->with('success', 'Competencia creada correctamente.');
    }

    public function show(Competencia $competencia)
    {
        $competencia->load('learningOutcomes');
        return view('competencias.show', compact('competencia'));
    }

    public function edit(Competencia $competencia)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para editar competencias.');
        }
        
        return view('competencias.edit', compact('competencia'));
    }

    public function update(Request $request, Competencia $competencia)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para actualizar competencias.');
        }
        
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'unique:competencias,codigo,' . $competencia->id, 'regex:/^[0-9]+$/'],
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ], [
            'codigo.regex' => 'El código solo puede contener números.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        ]);

        $competencia->update($validated);

        return redirect()->route('programs.competencias.index', $competencia->program)
            ->with('success', 'Competencia actualizada correctamente.');
    }

    public function destroy(Competencia $competencia)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para eliminar competencias.');
        }
        
        if ($competencia->learningOutcomes()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una competencia con resultados de aprendizaje asociados.');
        }

        $program = $competencia->program;
        $competencia->delete();

        return redirect()->route('programs.competencias.index', $program)
            ->with('success', 'Competencia eliminada correctamente.');
    }

    public function assignInstructors(Competencia $competencia)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para asignar instructores.');
        }
        
        $instructors = \App\Models\Instructor::where('activo', true)->get();
        $assignedInstructors = $competencia->instructors->pluck('id')->toArray();
        
        return view('competencias.assign_instructors', compact('competencia', 'instructors', 'assignedInstructors'));
    }

    public function storeInstructors(Request $request, Competencia $competencia)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para asignar instructores.');
        }
        
        $validated = $request->validate([
            'instructors' => 'required|array',
            'instructors.*' => 'exists:instructors,id',
        ]);
        
        $competencia->instructors()->sync($validated['instructors']);
        
        return redirect()->route('programs.competencias.index', $competencia->program)
            ->with('success', 'Instructores asignados correctamente.');
    }
}
