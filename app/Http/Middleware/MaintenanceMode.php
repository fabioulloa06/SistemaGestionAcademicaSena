<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maintenanceFile = storage_path('app/maintenance.json');
        
        // Verificar si el modo mantenimiento está activo
        if (File::exists($maintenanceFile)) {
            $maintenance = json_decode(File::get($maintenanceFile), true);
            
            if (isset($maintenance['enabled']) && $maintenance['enabled'] === true) {
                // Permitir acceso a rutas de mantenimiento y login
                $allowedRoutes = [
                    'maintenance.index',
                    'maintenance.enable',
                    'maintenance.disable',
                    'maintenance.status',
                    'login',
                ];
                
                $routeName = $request->route()?->getName();
                
                // Si es admin autenticado, permitir acceso a todo
                if (auth()->check() && auth()->user()->isAdmin()) {
                    return $next($request);
                }
                
                // Permitir logout para todos los usuarios autenticados
                if ($routeName === 'logout' && auth()->check()) {
                    return $next($request);
                }
                
                // Si es una ruta permitida, continuar
                if (in_array($routeName, $allowedRoutes)) {
                    return $next($request);
                }
                
                // Si es una petición AJAX, retornar JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'El sistema está en modo mantenimiento. Por favor, intente más tarde.',
                        'maintenance' => true,
                    ], 503);
                }
                
                // Redirigir a la página de mantenimiento
                return response()->view('maintenance.index', [
                    'message' => $maintenance['message'] ?? 'El sistema está en mantenimiento. Por favor, intente más tarde.',
                    'estimated_time' => $maintenance['estimated_time'] ?? null,
                ], 503);
            }
        }
        
        return $next($request);
    }
}

