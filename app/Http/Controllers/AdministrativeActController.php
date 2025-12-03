<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeAct;
use App\Models\AdministrativeProcedure;
use App\Models\DisciplinaryAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdministrativeActController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'No tienes permiso para ver actos administrativos.');
        }

        $acts = AdministrativeAct::with(['student', 'disciplinaryAction', 'expedidoPor'])
            ->orderBy('fecha_expedicion', 'desc')
            ->paginate(15);

        return view('administrative_acts.index', compact('acts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(AdministrativeProcedure $administrativeProcedure)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'Solo administradores y coordinadores pueden expedir actos administrativos.');
        }

        if ($administrativeProcedure->estado !== 'en_comite') {
            return back()->withErrors(['error' => 'El procedimiento debe estar en etapa de comité para expedir el acto administrativo.']);
        }

        if ($administrativeProcedure->acto_administrativo_id) {
            return redirect()->route('administrative-acts.show', $administrativeProcedure->administrativeAct)
                ->with('info', 'Ya existe un acto administrativo para este procedimiento.');
        }

        $administrativeProcedure->load([
            'student',
            'disciplinaryAction.disciplinaryFault',
            'disciplinaryAction.academicFault',
            'studentDischarges'
        ]);

        return view('administrative_acts.create', compact('administrativeProcedure'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, AdministrativeProcedure $administrativeProcedure)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'Solo administradores y coordinadores pueden expedir actos administrativos.');
        }

        $validated = $request->validate([
            'tipo_acto' => 'required|in:sancionatorio,no_sancionatorio',
            'relacion_hechos' => 'required|string|min:50',
            'relacion_descargos' => 'required|string',
            'relacion_pruebas' => 'required|string',
            'normas_infringidas' => 'required|string',
            'identificacion_autores' => 'required|string',
            'grado_responsabilidad' => 'required|string',
            'calificacion_falta' => 'required|in:Leve,Grave,Gravísima',
            'tipo_falta' => 'required|in:Académica,Disciplinaria,Académica y Disciplinaria',
            'razones_decision' => 'required|string|min:50',
            'referencia_recomendacion_comite' => 'nullable|string',
            'apartado_recomendacion_comite' => 'boolean',
            'razones_apartamiento' => 'required_if:apartado_recomendacion_comite,1|nullable|string',
            'tipo_sancion' => 'required_if:tipo_acto,sancionatorio|nullable|in:amonestacion_escrita,plan_mejoramiento,condicionamiento_matricula,suspension_temporal,cancelacion_matricula,ninguna',
            'descripcion_sancion' => 'required_if:tipo_acto,sancionatorio|nullable|string',
            'instructor_seguimiento_id' => 'required_if:tipo_sancion,condicionamiento_matricula|nullable|exists:users,id',
            'plan_mejoramiento_designado' => 'nullable|string',
            'recursos_procedentes' => 'required|string',
            'forma_recurso' => 'required|string',
            'plazo_recurso' => 'required|integer|min:1|max:30',
            'autoridad_recurso' => 'required|string',
        ]);

        // Generar número único del acto
        $numeroActo = 'ACT-' . date('Y') . '-' . str_pad(AdministrativeAct::count() + 1, 5, '0', STR_PAD_LEFT);

        $act = AdministrativeAct::create([
            'disciplinary_action_id' => $administrativeProcedure->disciplinary_action_id,
            'administrative_procedure_id' => $administrativeProcedure->id,
            'student_id' => $administrativeProcedure->student_id,
            'numero_acto' => $numeroActo,
            'tipo_acto' => $validated['tipo_acto'],
            'relacion_hechos' => $validated['relacion_hechos'],
            'relacion_descargos' => $validated['relacion_descargos'],
            'relacion_pruebas' => $validated['relacion_pruebas'],
            'normas_infringidas' => $validated['normas_infringidas'],
            'identificacion_autores' => $validated['identificacion_autores'],
            'grado_responsabilidad' => $validated['grado_responsabilidad'],
            'calificacion_falta' => $validated['calificacion_falta'],
            'tipo_falta' => $validated['tipo_falta'],
            'razones_decision' => $validated['razones_decision'],
            'referencia_recomendacion_comite' => $validated['referencia_recomendacion_comite'],
            'tipo_sancion' => $validated['tipo_sancion'] ?? null,
            'descripcion_sancion' => $validated['descripcion_sancion'] ?? null,
            'instructor_seguimiento_id' => $validated['instructor_seguimiento_id'] ?? null,
            'plan_mejoramiento_designado' => $validated['plan_mejoramiento_designado'] ?? null,
            'recursos_procedentes' => $validated['recursos_procedentes'],
            'forma_recurso' => $validated['forma_recurso'],
            'plazo_recurso' => $validated['plazo_recurso'],
            'autoridad_recurso' => $validated['autoridad_recurso'],
            'expedido_por' => $user->id,
            'fecha_expedicion' => now(),
        ]);

        // Actualizar procedimiento y llamado de atención
        $administrativeProcedure->update([
            'acto_administrativo_id' => $act->id,
            'fecha_acto' => now(),
            'estado' => 'acto_expedido',
            'motivacion_decision' => $validated['razones_decision'],
            'apartado_recomendacion_comite' => $validated['apartado_recomendacion_comite'] ?? false,
            'razones_apartamiento' => $validated['razones_apartamiento'] ?? null,
        ]);

        $administrativeProcedure->disciplinaryAction->update([
            'administrative_act_id' => $act->id,
            'estado_procedimiento' => 'acto_expedido',
        ]);

        return redirect()->route('administrative-acts.show', $act)
            ->with('success', 'Acto administrativo expedido correctamente. Debe ser notificado al aprendiz.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdministrativeAct $administrativeAct)
    {
        $user = Auth::user();
        
        if (!$user->canViewDisciplinaryActions()) {
            abort(403, 'No tienes permiso para ver este acto administrativo.');
        }

        $administrativeAct->load([
            'student.group.program',
            'disciplinaryAction.disciplinaryFault',
            'disciplinaryAction.academicFault',
            'administrativeProcedure',
            'expedidoPor',
            'instructorSeguimiento',
            'appeals'
        ]);

        return view('administrative_acts.show', compact('administrativeAct'));
    }

    /**
     * Notificar el acto administrativo al aprendiz
     */
    public function notify(AdministrativeAct $administrativeAct, Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'Solo administradores y coordinadores pueden notificar actos administrativos.');
        }

        $validated = $request->validate([
            'metodo_notificacion' => 'required|in:personal,correo_electronico,aviso_domicilio,aviso_publico',
            'fecha_notificacion' => 'required|date',
        ]);

        $administrativeAct->update([
            'metodo_notificacion' => $validated['metodo_notificacion'],
            'fecha_notificacion' => $validated['fecha_notificacion'],
            'notificado' => true,
        ]);

        $administrativeAct->disciplinaryAction->update([
            'fecha_notificacion' => $validated['fecha_notificacion'],
            'metodo_notificacion' => $validated['metodo_notificacion'],
            'notificado' => true,
            'estado_procedimiento' => 'notificado',
        ]);

        $administrativeAct->administrativeProcedure->update([
            'estado' => 'notificado',
        ]);

        // Enviar correo si es notificación por correo electrónico
        if ($validated['metodo_notificacion'] === 'correo_electronico' && $administrativeAct->student->user) {
            // TODO: Crear Mailable para notificación de acto administrativo
            // \Illuminate\Support\Facades\Mail::to($administrativeAct->student->user->email)
            //     ->send(new \App\Mail\AdministrativeActNotification($administrativeAct));
        }

        return back()->with('success', 'Acto administrativo notificado correctamente.');
    }

    /**
     * Marcar acto como firme
     */
    public function markAsFirm(AdministrativeAct $administrativeAct)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'Solo administradores y coordinadores pueden marcar actos como firmes.');
        }

        if (!$administrativeAct->puedeQuedarFirme()) {
            return back()->withErrors(['error' => 'El acto no puede quedar firme aún. Hay recursos pendientes o no se ha cumplido el plazo.']);
        }

        $administrativeAct->update([
            'firme' => true,
            'fecha_firmeza' => now(),
        ]);

        $administrativeAct->disciplinaryAction->update([
            'estado_procedimiento' => 'firme',
        ]);

        $administrativeAct->administrativeProcedure->update([
            'estado' => 'firme',
        ]);

        return back()->with('success', 'Acto administrativo marcado como firme.');
    }

    /**
     * Imprimir acto administrativo
     */
    public function print(AdministrativeAct $administrativeAct)
    {
        $user = Auth::user();
        
        if (!$user->canViewDisciplinaryActions()) {
            abort(403, 'No tienes permiso para imprimir este acto administrativo.');
        }

        $administrativeAct->load([
            'student.group.program',
            'disciplinaryAction.disciplinaryFault',
            'disciplinaryAction.academicFault',
            'administrativeProcedure.studentDischarges',
            'expedidoPor',
            'instructorSeguimiento'
        ]);

        return view('administrative_acts.print', compact('administrativeAct'));
    }
}
