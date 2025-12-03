<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    /**
     * Mostrar el estado del modo mantenimiento
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Solo los administradores pueden gestionar el modo mantenimiento.');
        }
        
        $maintenanceFile = storage_path('app/maintenance.json');
        $maintenance = [
            'enabled' => false,
            'message' => 'El sistema está en mantenimiento. Por favor, intente más tarde.',
            'estimated_time' => null,
            'activated_at' => null,
            'activated_by' => null,
        ];
        
        if (File::exists($maintenanceFile)) {
            $maintenance = array_merge($maintenance, json_decode(File::get($maintenanceFile), true));
        }
        
        return view('maintenance.manage', compact('maintenance'));
    }
    
    /**
     * Activar modo mantenimiento
     */
    public function enable(Request $request)
    {
        $user = auth()->user();
        
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Solo los administradores pueden activar el modo mantenimiento.');
        }
        
        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
            'estimated_time' => 'nullable|string|max:100',
        ]);
        
        $maintenance = [
            'enabled' => true,
            'message' => $validated['message'] ?? 'El sistema está en mantenimiento. Por favor, intente más tarde.',
            'estimated_time' => $validated['estimated_time'] ?? null,
            'activated_at' => now()->toDateTimeString(),
            'activated_by' => $user->id,
            'activated_by_name' => $user->name,
        ];
        
        File::put(storage_path('app/maintenance.json'), json_encode($maintenance, JSON_PRETTY_PRINT));
        
        return redirect()->route('maintenance.index')
            ->with('success', 'Modo mantenimiento activado correctamente.');
    }
    
    /**
     * Desactivar modo mantenimiento
     */
    public function disable()
    {
        $user = auth()->user();
        
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Solo los administradores pueden desactivar el modo mantenimiento.');
        }
        
        $maintenanceFile = storage_path('app/maintenance.json');
        
        if (File::exists($maintenanceFile)) {
            $maintenance = json_decode(File::get($maintenanceFile), true);
            $maintenance['enabled'] = false;
            $maintenance['deactivated_at'] = now()->toDateTimeString();
            $maintenance['deactivated_by'] = $user->id;
            $maintenance['deactivated_by_name'] = $user->name;
            
            File::put(storage_path('app/maintenance.json'), json_encode($maintenance, JSON_PRETTY_PRINT));
        }
        
        return redirect()->route('maintenance.index')
            ->with('success', 'Modo mantenimiento desactivado correctamente.');
    }
    
    /**
     * Obtener estado del modo mantenimiento (API)
     */
    public function status()
    {
        $maintenanceFile = storage_path('app/maintenance.json');
        
        if (File::exists($maintenanceFile)) {
            $maintenance = json_decode(File::get($maintenanceFile), true);
            return response()->json([
                'enabled' => $maintenance['enabled'] ?? false,
                'message' => $maintenance['message'] ?? null,
                'estimated_time' => $maintenance['estimated_time'] ?? null,
            ]);
        }
        
        return response()->json(['enabled' => false]);
    }
}

