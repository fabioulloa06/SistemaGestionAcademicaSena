<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Group::with('program')->withCount('students');

        if ($request->has('search')) {
            $query->where('numero_ficha', 'like', "%{$request->search}%");
        }

        if ($request->has('programa_id') && $request->programa_id != '') {
            $query->where('program_id', $request->programa_id);
        }

        if ($request->has('jornada') && $request->jornada != '') {
            $query->where('jornada', $request->jornada);
        }

        $groups = $query->paginate(10);
        $programs = \App\Models\Program::where('activo', true)->get();

        return view('groups.index', compact('groups', 'programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = \App\Models\Program::where('activo', true)->get();
        return view('groups.create', compact('programs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_ficha' => 'required|string|unique:groups,numero_ficha',
            'program_id' => 'required|exists:programs,id',
            'jornada' => 'required|in:mañana,tarde,noche',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'activo' => 'boolean',
        ]);

        \App\Models\Group::create($validated);

        return redirect()->route('groups.index')
            ->with('success', 'Grupo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $group = \App\Models\Group::with(['program', 'students'])->findOrFail($id);
        return view('groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $group = \App\Models\Group::findOrFail($id);
        $programs = \App\Models\Program::where('activo', true)->get();
        return view('groups.edit', compact('group', 'programs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $group = \App\Models\Group::findOrFail($id);

        $validated = $request->validate([
            'numero_ficha' => 'required|string|unique:groups,numero_ficha,' . $group->id,
            'program_id' => 'required|exists:programs,id',
            'jornada' => 'required|in:mañana,tarde,noche',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'activo' => 'boolean',
        ]);

        // Handle checkbox unchecked (not present in request)
        if (!$request->has('activo')) {
            $validated['activo'] = false;
        }

        $group->update($validated);

        return redirect()->route('groups.index')
            ->with('success', 'Grupo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = \App\Models\Group::findOrFail($id);
        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'Grupo eliminado exitosamente.');
    }
}
