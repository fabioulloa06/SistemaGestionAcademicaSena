<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = \App\Models\Student::with('group.program');

        // Instructores solo ven estudiantes de sus grupos asignados
        if ($user->isInstructor()) {
            $assignments = \App\Models\CompetenciaGroupInstructor::where('instructor_id', $user->id)->get();
            $groupIds = $assignments->pluck('group_id')->unique();
            
            $query->whereIn('group_id', $groupIds);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('documento', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('grupo_id') && $request->grupo_id != '') {
            $query->where('group_id', $request->grupo_id);
        }

        $students = $query->paginate(10);
        
        // Filtrar grupos según rol
        $groups = \App\Models\Group::with('program');
        if ($user->isInstructor()) {
            $assignments = \App\Models\CompetenciaGroupInstructor::where('instructor_id', $user->id)->get();
            $groupIds = $assignments->pluck('group_id')->unique();
            $groups->whereIn('id', $groupIds);
        }
        $groups = $groups->get();

        return view('students.index', compact('students', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = \App\Models\Group::with('program')->where('activo', true)->get();
        return view('students.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|unique:students,documento',
            'email' => 'required|email|unique:students,email',
            'telefono' => 'nullable|string|max:20',
            'group_id' => 'required|exists:groups,id',
        ]);

        $student = \App\Models\Student::create($validated);

        // Crear Usuario para el Estudiante
        $user = \App\Models\User::create([
            'name' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['documento']), // Contraseña = Documento
        ]);

        $student->user_id = $user->id;
        $student->save();

        return redirect()->route('students.index')
            ->with('success', 'Estudiante creado exitosamente. Se ha generado su usuario de acceso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = \App\Models\Student::with(['group.program', 'attendances'])->findOrFail($id);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $groups = \App\Models\Group::with('program')->where('activo', true)->get();
        return view('students.edit', compact('student', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = \App\Models\Student::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|unique:students,documento,' . $student->id,
            'email' => 'required|email|unique:students,email,' . $student->id,
            'telefono' => 'nullable|string|max:20',
            'group_id' => 'required|exists:groups,id',
        ]);

        $student->update($validated);

        // Actualizar Usuario asociado si existe
        if ($student->user) {
            $student->user->update([
                'name' => $validated['nombre'],
                'email' => $validated['email'],
            ]);
        } else {
            // Si no tenía usuario (migración de datos antiguos), crearlo
            $user = \App\Models\User::create([
                'name' => $validated['nombre'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['documento']),
            ]);
            $student->user_id = $user->id;
            $student->save();
        }

        return redirect()->route('students.index')
            ->with('success', 'Estudiante actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Estudiante eliminado exitosamente.');
    }
}
