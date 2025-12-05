<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Mapear roles en español a inglés si es necesario
        $roleMap = [
            'admin' => 'admin',
            'coordinator' => 'coordinator',
            'coordinador' => 'coordinator',
            'instructor' => 'instructor',
            'instructor_lider' => 'instructor',
            'student' => 'student',
            'aprendiz' => 'student',
        ];
        
        // Normalizar roles del parámetro
        $normalizedRoles = array_map(function($role) use ($roleMap) {
            return $roleMap[$role] ?? $role;
        }, $roles);
        
        // Obtener rol del usuario (priorizar 'role', luego 'rol')
        $userRole = $user->role;
        
        // Normalizar rol del usuario
        $normalizedUserRole = $roleMap[$userRole] ?? $userRole;

        if (!in_array($normalizedUserRole, $normalizedRoles)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
