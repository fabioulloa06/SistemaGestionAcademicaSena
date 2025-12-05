<?php

namespace App\Http\Controllers;

use App\Models\StudentDischarge;
use App\Models\DisciplinaryAction;
use App\Models\AdministrativeProcedure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentDischargeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(DisciplinaryAction $disciplinaryAction)
    {
        $user = Auth::user();
        
        // Solo el estudiante puede presentar descargos
        $studentUser = $disciplinaryAction->student->user;
        if (!$user->isStudent() || !$studentUser || $user->id !== $studentUser->id) {
            abort(403, 'Solo el aprendiz puede presentar descargos.');
        }

        if (!$disciplinaryAction->puedePresentarDescargos()) {
            abort(403, 'No puedes presentar descargos en este momento. El procedimiento no está en etapa de investigación.');
        }

        // Verificar si ya tiene descargos presentados
        $existingDischarge = $disciplinaryAction->studentDischarges()
            ->where('estado', '!=', 'rechazado')
            ->first();

        if ($existingDischarge) {
            return redirect()->route('student-discharges.show', $existingDischarge)
                ->with('info', 'Ya has presentado descargos para este llamado de atención.');
        }

        return view('student_discharges.create', compact('disciplinaryAction'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DisciplinaryAction $disciplinaryAction)
    {
        $user = Auth::user();
        
        $studentUser = $disciplinaryAction->student->user;
        if (!$user->isStudent() || !$studentUser || $user->id !== $studentUser->id) {
            abort(403, 'Solo el aprendiz puede presentar descargos.');
        }

        if (!$disciplinaryAction->puedePresentarDescargos()) {
            abort(403, 'No puedes presentar descargos en este momento.');
        }

        $validated = $request->validate([
            'texto_descargo' => 'required|string|min:50',
            'archivo_adjunto' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
            'pruebas_aportadas' => 'nullable|array',
            'pruebas_aportadas.*' => 'string',
        ]);

        $procedure = $disciplinaryAction->administrativeProcedure;

        $discharge = StudentDischarge::create([
            'disciplinary_action_id' => $disciplinaryAction->id,
            'administrative_procedure_id' => $procedure?->id,
            'student_id' => $disciplinaryAction->student_id,
            'texto_descargo' => $validated['texto_descargo'],
            'pruebas_aportadas' => $validated['pruebas_aportadas'] ?? [],
            'fecha_presentacion' => now(),
            'estado' => 'presentado',
        ]);

        // Guardar archivo adjunto si existe
        if ($request->hasFile('archivo_adjunto')) {
            $path = $request->file('archivo_adjunto')->store('descargos', 'public');
            $discharge->update(['archivo_adjunto' => $path]);
        }

        // Actualizar estado del procedimiento
        if ($procedure) {
            $procedure->update(['estado' => 'descargos_presentados']);
            $disciplinaryAction->update(['estado_procedimiento' => 'descargos_presentados']);
        }

        return redirect()->route('student-discharges.show', $discharge)
            ->with('success', 'Descargos presentados correctamente. Serán revisados por la Subdirección de Centro.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentDischarge $studentDischarge)
    {
        $user = Auth::user();
        
        // El estudiante puede ver sus propios descargos, admin y coordinador pueden ver todos
        $studentUser = $studentDischarge->student->user;
        if ($user->isStudent() && (!$studentUser || $user->id !== $studentUser->id)) {
            abort(403, 'No tienes permiso para ver estos descargos.');
        }

        if (!$user->isStudent() && !$user->canViewDisciplinaryActions()) {
            abort(403, 'No tienes permiso para ver descargos.');
        }

        $studentDischarge->load([
            'disciplinaryAction.disciplinaryFault',
            'disciplinaryAction.academicFault',
            'student.group.program',
            'revisadoPor',
            'administrativeProcedure'
        ]);

        return view('student_discharges.show', compact('studentDischarge'));
    }

    /**
     * Solicitar ampliación de descargos (5 días hábiles según Acuerdo 009)
     */
    public function requestExtension(StudentDischarge $studentDischarge, Request $request)
    {
        $user = Auth::user();
        
        $studentUser = $studentDischarge->student->user;
        if (!$user->isStudent() || !$studentUser || $user->id !== $studentUser->id) {
            abort(403, 'Solo el aprendiz puede solicitar ampliación de descargos.');
        }

        if (!$studentDischarge->puedeAmpliar()) {
            abort(403, 'No puedes solicitar ampliación de descargos en este momento.');
        }

        $validated = $request->validate([
            'solicitud_ampliacion' => 'required|string|min:20',
        ]);

        $studentDischarge->update([
            'solicitud_ampliacion' => $validated['solicitud_ampliacion'],
            'fecha_ampliacion' => now(),
            'estado' => 'ampliado',
        ]);

        return back()->with('success', 'Solicitud de ampliación de descargos presentada. Tienes 5 días hábiles para ampliar.');
    }

    /**
     * Revisar descargos (Admin/Coordinador)
     */
    public function review(StudentDischarge $studentDischarge, Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'Solo administradores y coordinadores pueden revisar descargos.');
        }

        $validated = $request->validate([
            'estado' => 'required|in:aceptado,rechazado',
            'observaciones_revision' => 'nullable|string',
        ]);

        $studentDischarge->update([
            'estado' => $validated['estado'],
            'observaciones_revision' => $validated['observaciones_revision'],
            'revisado_por' => $user->id,
            'fecha_revision' => now(),
        ]);

        $message = $validated['estado'] === 'aceptado' 
            ? 'Descargos aceptados.' 
            : 'Descargos rechazados.';

        return back()->with('success', $message);
    }
}
