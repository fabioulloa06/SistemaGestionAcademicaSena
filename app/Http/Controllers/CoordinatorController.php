<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CoordinatorController extends Controller
{
    /**
     * Display a listing of coordinators.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Solo admin puede ver y gestionar coordinadores
        if (!$user->isAdmin()) {
            abort(403, 'No tienes permiso para ver coordinadores académicos.');
        }
        
        $coordinators = User::where('role', 'coordinator')
            ->orWhere('rol', 'coordinador')
            ->orderBy('name')
            ->paginate(15);
        
        return view('coordinators.index', compact('coordinators'));
    }

    /**
     * Show the form for creating a new coordinator.
     */
    public function create()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin()) {
            abort(403, 'No tienes permiso para crear coordinadores académicos.');
        }
        
        return view('coordinators.create');
    }

    /**
     * Store a newly created coordinator.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin()) {
            abort(403, 'No tienes permiso para crear coordinadores académicos.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
        ]);

        $coordinator = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'coordinator',
            'telefono' => $validated['telefono'] ?? null,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('coordinators.index')
            ->with('success', 'Coordinador académico creado exitosamente.');
    }

    /**
     * Display the specified coordinator.
     */
    public function show(User $coordinator)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin()) {
            abort(403, 'No tienes permiso para ver este coordinador.');
        }
        
        // Verificar que sea coordinador
        if (!$coordinator->isCoordinator()) {
            abort(404, 'El usuario especificado no es un coordinador.');
        }
        
        return view('coordinators.show', compact('coordinator'));
    }

    /**
     * Show the form for editing the specified coordinator.
     */
    public function edit(User $coordinator)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin()) {
            abort(403, 'No tienes permiso para editar coordinadores.');
        }
        
        if (!$coordinator->isCoordinator()) {
            abort(404, 'El usuario especificado no es un coordinador.');
        }
        
        return view('coordinators.edit', compact('coordinator'));
    }

    /**
     * Update the specified coordinator.
     */
    public function update(Request $request, User $coordinator)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin()) {
            abort(403, 'No tienes permiso para actualizar coordinadores.');
        }
        
        if (!$coordinator->isCoordinator()) {
            abort(404, 'El usuario especificado no es un coordinador.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $coordinator->id,
            'password' => 'nullable|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
        ]);

        $coordinator->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
        ]);

        if (!empty($validated['password'])) {
            $coordinator->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('coordinators.index')
            ->with('success', 'Coordinador académico actualizado exitosamente.');
    }

    /**
     * Remove the specified coordinator.
     */
    public function destroy(User $coordinator)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin()) {
            abort(403, 'No tienes permiso para eliminar coordinadores.');
        }
        
        if (!$coordinator->isCoordinator()) {
            abort(404, 'El usuario especificado no es un coordinador.');
        }
        
        // Verificar que no tenga grupos asignados como coordinador
        // (si existe esa relación en el futuro)
        
        $coordinator->delete();

        return redirect()->route('coordinators.index')
            ->with('success', 'Coordinador académico eliminado exitosamente.');
    }
}

