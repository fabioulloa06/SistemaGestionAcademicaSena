<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     * Verifica permisos específicos del usuario
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Verificar permisos según el tipo
        $hasPermission = match($permission) {
            'manage-users' => $user->canManageUsers(),
            'manage-academic-structure' => $user->canManageAcademicStructure(),
            'grade' => $user->canGrade(),
            'manage-attendance' => $user->canManageAttendance(),
            'create-disciplinary-actions' => $user->canCreateDisciplinaryActions(),
            'view-disciplinary-actions' => $user->canViewDisciplinaryActions(),
            'view-reports' => $user->canViewReports(),
            'view-audit' => $user->canViewAudit(),
            'view-all' => $user->canViewAll(),
            default => false,
        };

        if (!$hasPermission) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        return $next($request);
    }
}

