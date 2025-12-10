<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Program::withCount('groups');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        $programs = $query->paginate(10);

        return view('programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para crear programas.');
        }
        
        return view('programs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para crear programas.');
        }
        
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'codigo' => ['required', 'string', 'unique:programs,codigo', 'regex:/^[0-9]+$/'],
            'descripcion' => 'nullable|string',
            'duracion_meses' => 'required|integer|min:1',
            'nivel' => 'required|string',
            'activo' => 'boolean',
        ], [
            'nombre.regex' => 'El nombre del programa solo puede contener letras y espacios.',
            'codigo.regex' => 'El código del programa solo puede contener números.',
        ]);

        \App\Models\Program::create($validated);

        return redirect()->route('programs.index')
            ->with('success', 'Programa creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $program = \App\Models\Program::with(['groups', 'competencias'])->findOrFail($id);
        return view('programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para editar programas.');
        }
        
        $program = \App\Models\Program::findOrFail($id);
        return view('programs.edit', compact('program'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para editar programas.');
        }
        
        $program = \App\Models\Program::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'codigo' => ['required', 'string', 'unique:programs,codigo,' . $program->id, 'regex:/^[0-9]+$/'],
            'descripcion' => 'nullable|string',
            'duracion_meses' => 'required|integer|min:1',
            'nivel' => 'required|string',
            'activo' => 'boolean',
        ], [
            'nombre.regex' => 'El nombre del programa solo puede contener letras y espacios.',
            'codigo.regex' => 'El código del programa solo puede contener números.',
        ]);

        // Handle checkbox unchecked
        if (!$request->has('activo')) {
            $validated['activo'] = false;
        }

        $program->update($validated);

        return redirect()->route('programs.index')
            ->with('success', 'Programa actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = \App\Models\Program::findOrFail($id);
        
        if ($program->groups()->exists()) {
            return redirect()->route('programs.index')
                ->with('error', 'No se puede eliminar el programa porque tiene grupos asociados.');
        }

        $program->delete();

        return redirect()->route('programs.index')
            ->with('success', 'Programa eliminado exitosamente.');
    }
}
