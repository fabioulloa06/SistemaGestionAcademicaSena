<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentPortalController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user()->student;

        if (!$student) {
            abort(403, 'No tienes un perfil de estudiante asociado.');
        }

        // Resumen de Asistencias
        $attendanceSummary = [
            'total' => $student->attendance_lists()->count(),
            'presente' => $student->attendance_lists()->where('estado', 'presente')->count(),
            'ausente' => $student->attendance_lists()->where('estado', 'ausente')->count(),
            'tarde' => $student->attendance_lists()->where('estado', 'tarde')->count(),
            'justificado' => $student->attendance_lists()->where('estado', 'justificado')->count(),
        ];

        // Últimas asistencias
        $recentAttendances = $student->attendance_lists()
            ->with('competencia')
            ->orderBy('fecha', 'desc')
            ->take(5)
            ->get();

        // Procesos Disciplinarios Activos
        $activeDisciplinary = $student->disciplinary_actions()->count();

        return view('student.dashboard', compact('student', 'attendanceSummary', 'recentAttendances', 'activeDisciplinary'));
    }
}
