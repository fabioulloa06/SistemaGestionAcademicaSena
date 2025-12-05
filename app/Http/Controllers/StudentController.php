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
        
        // Verificar permisos
        if (!$user->canManageAcademicStructure() && !$user->isInstructor()) {
            abort(403, 'No tienes permiso para ver estudiantes.');
        }
        
        $groupIds = $user->getAccessibleGroupIds();
        $query = \App\Models\Student::with(['group' => function($q) {
            $q->with('program');
        }])->where('activo', true);

        // Filtrar por grupos accesibles
        if ($user->isInstructor() || $user->isStudent()) {
            $query->whereIn('group_id', $groupIds);
        }

        // Búsqueda
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('documento', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por grupo
        if ($request->has('grupo_id') && $request->grupo_id != '') {
            // Verificar que el grupo sea accesible
            if ($groupIds->contains($request->grupo_id) || $user->canManageAcademicStructure()) {
                $query->where('group_id', $request->grupo_id);
            }
        }

        $students = $query->orderBy('nombre')->paginate(15);
        
        // Filtrar grupos según rol
        $groupsQuery = \App\Models\Group::with('program')->where('activo', true);
        if ($user->isInstructor() || $user->isStudent()) {
            $groupsQuery->whereIn('id', $groupIds);
        }
        $groups = $groupsQuery->orderBy('numero_ficha')->get();

        return view('students.index', compact('students', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Coordinador solo puede ver, no crear
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para crear estudiantes. Tu rol es de revisión y vigilancia.');
        }
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para crear estudiantes.');
        }
        
        $groups = \App\Models\Group::with('program')->where('activo', true)->get();
        return view('students.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Coordinador solo puede ver, no crear
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para crear estudiantes. Tu rol es de revisión y vigilancia.');
        }
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para crear estudiantes.');
        }
        
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
        $student = \App\Models\Student::with(['group.program', 'attendance_lists'])->findOrFail($id);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        
        // Coordinador solo puede ver, no editar
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para editar estudiantes. Tu rol es de revisión y vigilancia.');
        }
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para editar estudiantes.');
        }
        
        $student = \App\Models\Student::findOrFail($id);
        $groups = \App\Models\Group::with('program')->where('activo', true)->get();
        return view('students.edit', compact('student', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        
        // Coordinador solo puede ver, no editar
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para editar estudiantes. Tu rol es de revisión y vigilancia.');
        }
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para editar estudiantes.');
        }
        
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
        $user = auth()->user();
        
        // Coordinador solo puede ver, no eliminar
        if ($user->isCoordinator()) {
            abort(403, 'No tienes permiso para eliminar estudiantes. Tu rol es de revisión y vigilancia.');
        }
        
        if (!$user->canManageAcademicStructure()) {
            abort(403, 'No tienes permiso para eliminar estudiantes.');
        }
        
        $student = \App\Models\Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Estudiante eliminado exitosamente.');
    }
}
