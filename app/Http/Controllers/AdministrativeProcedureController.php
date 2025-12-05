<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeProcedure;
use App\Models\DisciplinaryAction;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdministrativeProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'No tienes permiso para ver procedimientos administrativos.');
        }

        $procedures = AdministrativeProcedure::with(['student', 'disciplinaryAction', 'iniciadoPor', 'investigadoPor'])
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(15);

        return view('administrative_procedures.index', compact('procedures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(DisciplinaryAction $disciplinaryAction)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'No tienes permiso para iniciar procedimientos administrativos.');
        }

        // Verificar que el llamado no tenga ya un procedimiento
        if ($disciplinaryAction->administrativeProcedure) {
            return redirect()->route('administrative-procedures.show', $disciplinaryAction->administrativeProcedure)
                ->with('info', 'Este llamado de atención ya tiene un procedimiento administrativo iniciado.');
        }

        return view('administrative_procedures.create', compact('disciplinaryAction'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DisciplinaryAction $disciplinaryAction)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'No tienes permiso para iniciar procedimientos administrativos.');
        }

        $validated = $request->validate([
            'tipo_falta' => 'required|in:Académica,Disciplinaria,Académica y Disciplinaria',
            'hechos_investigados' => 'nullable|string',
        ]);

        $procedure = AdministrativeProcedure::create([
            'disciplinary_action_id' => $disciplinaryAction->id,
            'student_id' => $disciplinaryAction->student_id,
            'tipo_falta' => $validated['tipo_falta'],
            'estado' => 'iniciado',
            'iniciado_por' => $user->id,
            'fecha_inicio' => now(),
            'hechos_investigados' => $validated['hechos_investigados'] ?? $disciplinaryAction->description,
        ]);

        // Actualizar el estado del llamado de atención
        $disciplinaryAction->update(['estado_procedimiento' => 'en_investigacion']);

        return redirect()->route('administrative-procedures.show', $procedure)
            ->with('success', 'Procedimiento administrativo iniciado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdministrativeProcedure $administrativeProcedure)
    {
        $user = Auth::user();
        
        if (!$user->canViewDisciplinaryActions()) {
            abort(403, 'No tienes permiso para ver este procedimiento.');
        }

        $administrativeProcedure->load([
            'student.group.program',
            'disciplinaryAction.disciplinaryFault',
            'disciplinaryAction.academicFault',
            'iniciadoPor',
            'investigadoPor',
            'administrativeAct',
            'studentDischarges.revisadoPor'
        ]);

        return view('administrative_procedures.show', compact('administrativeProcedure'));
    }

    /**
     * Iniciar investigación del procedimiento
     */
    public function startInvestigation(AdministrativeProcedure $administrativeProcedure)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'Solo administradores y coordinadores pueden iniciar investigaciones.');
        }

        if ($administrativeProcedure->estado !== 'iniciado') {
            return back()->withErrors(['error' => 'El procedimiento ya está en otra etapa.']);
        }

        $administrativeProcedure->update([
            'estado' => 'en_investigacion',
            'investigado_por' => $user->id,
            'fecha_investigacion' => now(),
        ]);

        $administrativeProcedure->disciplinaryAction->update([
            'estado_procedimiento' => 'en_investigacion',
            'investigado_por' => $user->id,
            'fecha_investigacion' => now(),
        ]);

        return back()->with('success', 'Investigación iniciada. El aprendiz puede presentar sus descargos.');
    }

    /**
     * Enviar a comité de evaluación
     */
    public function sendToCommittee(AdministrativeProcedure $administrativeProcedure, Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'Solo administradores y coordinadores pueden enviar a comité.');
        }

        $validated = $request->validate([
            'recomendacion_comite' => 'required|string',
            'fecha_comite' => 'required|date',
        ]);

        $administrativeProcedure->update([
            'estado' => 'en_comite',
            'fecha_comite' => $validated['fecha_comite'],
            'recomendacion_comite' => $validated['recomendacion_comite'],
        ]);

        $administrativeProcedure->disciplinaryAction->update([
            'estado_procedimiento' => 'en_comite',
            'fecha_comite' => $validated['fecha_comite'],
            'recomendacion_comite' => $validated['recomendacion_comite'],
        ]);

        return back()->with('success', 'Procedimiento enviado a comité de evaluación y seguimiento.');
    }
}
