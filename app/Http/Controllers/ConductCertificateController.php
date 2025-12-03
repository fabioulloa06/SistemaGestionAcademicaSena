<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\DisciplinaryAction;
use App\Models\AdministrativeAct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ConductCertificateController extends Controller
{
    /**
     * Generar certificado de conducta según Artículo 52º del Acuerdo 009 de 2024
     */
    public function generate(Student $student, Request $request)
    {
        $user = Auth::user();
        
        // Solo el estudiante puede solicitar su propio certificado, o admin/coordinador pueden generarlo
        $studentUser = $student->user;
        if ($user->isStudent() && (!$studentUser || $user->id !== $studentUser->id)) {
            abort(403, 'Solo puedes solicitar tu propio certificado de conducta.');
        }

        if (!$user->isStudent() && !$user->canViewDisciplinaryActions()) {
            abort(403, 'No tienes permiso para generar certificados de conducta.');
        }

        // Verificar autorización del estudiante si es solicitado por tercero
        $authorized = $request->boolean('authorized', true);
        
        if (!$authorized && !$user->isStudent()) {
            abort(403, 'El aprendiz debe autorizar la expedición del certificado.');
        }

        // Obtener sanciones según Artículo 52º:
        // - Solo sanciones vigentes o impuestas en los últimos 5 años
        $fiveYearsAgo = Carbon::now()->subYears(5);
        
        $disciplinaryActions = DisciplinaryAction::where('student_id', $student->id)
            ->where('tipo_llamado', 'written')
            ->where('date', '>=', $fiveYearsAgo)
            ->with(['disciplinaryFault', 'academicFault'])
            ->orderBy('date', 'desc')
            ->get();

        $administrativeActs = AdministrativeAct::where('student_id', $student->id)
            ->where('firme', true)
            ->where('fecha_expedicion', '>=', $fiveYearsAgo)
            ->whereNotNull('tipo_sancion')
            ->where('tipo_sancion', '!=', 'ninguna')
            ->orderBy('fecha_expedicion', 'desc')
            ->get();

        $student->load(['group.program']);

        return view('conduct_certificates.generate', compact('student', 'disciplinaryActions', 'administrativeActs', 'authorized'));
    }

    /**
     * Imprimir certificado de conducta
     */
    public function print(Student $student, Request $request)
    {
        $user = Auth::user();
        
        $studentUser = $student->user;
        if ($user->isStudent() && (!$studentUser || $user->id !== $studentUser->id)) {
            abort(403, 'Solo puedes imprimir tu propio certificado.');
        }

        if (!$user->isStudent() && !$user->canViewDisciplinaryActions()) {
            abort(403, 'No tienes permiso para imprimir certificados.');
        }

        $authorized = $request->boolean('authorized', true);
        
        if (!$authorized && !$user->isStudent()) {
            abort(403, 'El aprendiz debe autorizar la expedición del certificado.');
        }

        $fiveYearsAgo = Carbon::now()->subYears(5);
        
        $disciplinaryActions = DisciplinaryAction::where('student_id', $student->id)
            ->where('tipo_llamado', 'written')
            ->where('date', '>=', $fiveYearsAgo)
            ->with(['disciplinaryFault', 'academicFault'])
            ->orderBy('date', 'desc')
            ->get();

        $administrativeActs = AdministrativeAct::where('student_id', $student->id)
            ->where('firme', true)
            ->where('fecha_expedicion', '>=', $fiveYearsAgo)
            ->whereNotNull('tipo_sancion')
            ->where('tipo_sancion', '!=', 'ninguna')
            ->orderBy('fecha_expedicion', 'desc')
            ->get();

        $student->load(['group.program']);

        return view('conduct_certificates.print', compact('student', 'disciplinaryActions', 'administrativeActs', 'authorized'));
    }
}
