<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Instructor::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('documento', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $instructors = $query->paginate(10);

        return view('instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('instructors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|unique:instructors,documento',
            'email' => 'required|email|unique:instructors,email',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'nullable|string|max:255',
            'activo' => 'boolean',
        ]);

        \App\Models\Instructor::create($validated);

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $instructor = \App\Models\Instructor::findOrFail($id);
        return view('instructors.show', compact('instructor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $instructor = \App\Models\Instructor::findOrFail($id);
        return view('instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $instructor = \App\Models\Instructor::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|unique:instructors,documento,' . $instructor->id,
            'email' => 'required|email|unique:instructors,email,' . $instructor->id,
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'nullable|string|max:255',
            'activo' => 'boolean',
        ]);

        // Handle checkbox unchecked
        if (!$request->has('activo')) {
            $validated['activo'] = false;
        }

        $instructor->update($validated);

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $instructor = \App\Models\Instructor::findOrFail($id);
        
        // Check if instructor has attendances
        if ($instructor->attendances()->exists()) {
            return redirect()->route('instructors.index')
                ->with('error', 'No se puede eliminar el instructor porque tiene asistencias registradas.');
        }

        $instructor->delete();

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor eliminado exitosamente.');
    }
}
