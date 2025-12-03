<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\AdministrativeAct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AppealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->canViewDisciplinaryActions()) {
            abort(403, 'No tienes permiso para ver recursos.');
        }

        $query = Appeal::with(['administrativeAct', 'student', 'presentadoAnte', 'resueltoPor']);

        // Estudiantes solo ven sus propios recursos
        if ($user->isStudent()) {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            if ($student) {
                $query->where('student_id', $student->id);
            } else {
                $query->whereRaw('1 = 0'); // No mostrar nada si no tiene estudiante asociado
            }
        }

        $appeals = $query->orderBy('fecha_presentacion', 'desc')->paginate(15);

        return view('appeals.index', compact('appeals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(AdministrativeAct $administrativeAct)
    {
        $user = Auth::user();
        
        // Solo el estudiante puede presentar recursos
        $studentUser = $administrativeAct->student->user;
        if (!$user->isStudent() || !$studentUser || $user->id !== $studentUser->id) {
            abort(403, 'Solo el aprendiz puede presentar recursos contra el acto administrativo.');
        }

        if (!$administrativeAct->notificado) {
            abort(403, 'El acto administrativo debe estar notificado para presentar recursos.');
        }

        if ($administrativeAct->firme) {
            abort(403, 'El acto administrativo ya está firme. No se pueden presentar más recursos.');
        }

        // Verificar si ya tiene un recurso pendiente
        $pendingAppeal = $administrativeAct->appeals()
            ->where('estado', '!=', 'resuelto')
            ->where('estado', '!=', 'desistido')
            ->first();

        if ($pendingAppeal) {
            return redirect()->route('appeals.show', $pendingAppeal)
                ->with('info', 'Ya tienes un recurso pendiente para este acto administrativo.');
        }

        return view('appeals.create', compact('administrativeAct'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, AdministrativeAct $administrativeAct)
    {
        $user = Auth::user();
        
        $studentUser = $administrativeAct->student->user;
        if (!$user->isStudent() || !$studentUser || $user->id !== $studentUser->id) {
            abort(403, 'Solo el aprendiz puede presentar recursos.');
        }

        if (!$administrativeAct->notificado || $administrativeAct->firme) {
            abort(403, 'No puedes presentar recursos en este momento.');
        }

        $validated = $request->validate([
            'tipo_recurso' => 'required|in:reposicion,apelacion',
            'motivos_recurso' => 'required|string|min:50',
            'archivo_adjunto' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'pruebas_aportadas' => 'nullable|array',
            'pruebas_aportadas.*' => 'string',
        ]);

        // Determinar ante quién se presenta el recurso
        // Reposición: Subdirección de Centro (mismo que expidió)
        // Apelación: Director Regional
        $presentadoAnte = $validated['tipo_recurso'] === 'reposicion' 
            ? $administrativeAct->expedido_por 
            : null; // TODO: Obtener Director Regional

        // Si es apelación y no hay Director Regional, usar admin
        if ($validated['tipo_recurso'] === 'apelacion' && !$presentadoAnte) {
            $presentadoAnte = \App\Models\User::where('role', 'admin')->first()?->id ?? $administrativeAct->expedido_por;
        }

        // Generar número único del recurso
        $numeroRecurso = 'REC-' . date('Y') . '-' . str_pad(Appeal::count() + 1, 5, '0', STR_PAD_LEFT);

        $appeal = Appeal::create([
            'administrative_act_id' => $administrativeAct->id,
            'disciplinary_action_id' => $administrativeAct->disciplinary_action_id,
            'student_id' => $administrativeAct->student_id,
            'tipo_recurso' => $validated['tipo_recurso'],
            'numero_recurso' => $numeroRecurso,
            'fecha_presentacion' => now(),
            'motivos_recurso' => $validated['motivos_recurso'],
            'pruebas_aportadas' => $validated['pruebas_aportadas'] ?? [],
            'estado' => 'presentado',
            'presentado_ante' => $presentadoAnte,
        ]);

        // Guardar archivo adjunto si existe
        if ($request->hasFile('archivo_adjunto')) {
            $path = $request->file('archivo_adjunto')->store('recursos', 'public');
            $appeal->update(['archivo_adjunto' => $path]);
        }

        // Actualizar estado del procedimiento
        $administrativeAct->disciplinaryAction->update([
            'estado_procedimiento' => 'en_recurso',
        ]);

        $administrativeAct->administrativeProcedure->update([
            'estado' => 'en_recurso',
        ]);

        return redirect()->route('appeals.show', $appeal)
            ->with('success', 'Recurso presentado correctamente. Será resuelto dentro de los plazos establecidos.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appeal $appeal)
    {
        $user = Auth::user();
        
        // El estudiante puede ver sus propios recursos, admin y coordinador pueden ver todos
        $studentUser = $appeal->student->user;
        if ($user->isStudent() && (!$studentUser || $user->id !== $studentUser->id)) {
            abort(403, 'No tienes permiso para ver este recurso.');
        }

        if (!$user->isStudent() && !$user->canViewDisciplinaryActions()) {
            abort(403, 'No tienes permiso para ver recursos.');
        }

        $appeal->load([
            'administrativeAct.student',
            'administrativeAct.expedidoPor',
            'student.group.program',
            'presentadoAnte',
            'resueltoPor'
        ]);

        return view('appeals.show', compact('appeal'));
    }

    /**
     * Resolver recurso (Admin/Coordinador)
     */
    public function resolve(Appeal $appeal, Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isCoordinator()) {
            abort(403, 'Solo administradores y coordinadores pueden resolver recursos.');
        }

        if (!$appeal->puedeResolver()) {
            return back()->withErrors(['error' => 'Este recurso no puede ser resuelto en este momento.']);
        }

        $validated = $request->validate([
            'decision' => 'required|in:favorable,desfavorable,parcialmente_favorable',
            'motivacion_resolucion' => 'required|string|min:50',
            'agrava_sancion' => 'boolean',
        ]);

        // Validar que no se agrave la sanción (prohibido por el Acuerdo 009)
        if ($validated['agrava_sancion']) {
            return back()->withErrors(['error' => 'No se puede agravar la sanción inicialmente impuesta según el Acuerdo 009 de 2024.']);
        }

        $appeal->update([
            'estado' => 'resuelto',
            'resuelto_por' => $user->id,
            'fecha_resolucion' => now(),
            'decision' => $validated['decision'],
            'motivacion_resolucion' => $validated['motivacion_resolucion'],
            'agrava_sancion' => false,
        ]);

        // Si el recurso es favorable y modifica el acto, actualizar
        if ($validated['decision'] === 'favorable') {
            // TODO: Implementar lógica para modificar o anular el acto según la resolución
        }

        // Si no hay más recursos pendientes, verificar si puede quedar firme
        $pendingAppeals = $appeal->administrativeAct->appeals()
            ->where('estado', '!=', 'resuelto')
            ->where('estado', '!=', 'desistido')
            ->count();

        if ($pendingAppeals === 0) {
            // El acto puede quedar firme si se cumplen las condiciones
            // Esto se manejará en el método markAsFirm del AdministrativeActController
        }

        return back()->with('success', 'Recurso resuelto correctamente.');
    }

    /**
     * Desistir del recurso
     */
    public function withdraw(Appeal $appeal)
    {
        $user = Auth::user();
        
        $studentUser = $appeal->student->user;
        if (!$user->isStudent() || !$studentUser || $user->id !== $studentUser->id) {
            abort(403, 'Solo el aprendiz puede desistir de su recurso.');
        }

        if ($appeal->estado === 'resuelto') {
            return back()->withErrors(['error' => 'No puedes desistir de un recurso ya resuelto.']);
        }

        $appeal->update([
            'estado' => 'desistido',
        ]);

        return back()->with('success', 'Has desistido del recurso.');
    }
}
