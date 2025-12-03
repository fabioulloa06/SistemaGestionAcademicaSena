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

        // Resumen de INASISTENCIAS (solo se registran inasistencias)
        $absenceSummary = [
            'total_inasistencias' => $student->attendance_lists()->where('estado', 'ausente')->count(),
            'consecutivas' => 0, // Se calculará después
            'por_competencia' => [], // Se calculará después
        ];

        // Calcular inasistencias consecutivas (2 o más días seguidos)
        $absences = $student->attendance_lists()
            ->where('estado', 'ausente')
            ->orderBy('fecha', 'desc')
            ->get()
            ->groupBy('competencia_id');

        $consecutiveCount = 0;
        foreach ($absences as $competenciaId => $competenceAbsences) {
            $dates = $competenceAbsences->pluck('fecha')->sort()->values();
            $currentConsecutive = 1;
            $maxConsecutive = 1;
            
            for ($i = 0; $i < $dates->count() - 1; $i++) {
                $diff = $dates[$i]->diffInDays($dates[$i + 1]);
                if ($diff === 1) {
                    $currentConsecutive++;
                    $maxConsecutive = max($maxConsecutive, $currentConsecutive);
                } else {
                    $currentConsecutive = 1;
                }
            }
            
            if ($maxConsecutive >= 2) {
                $consecutiveCount++;
            }
        }
        $absenceSummary['consecutivas'] = $consecutiveCount;

        // Contar inasistencias por competencia
        $absencesByCompetence = $student->attendance_lists()
            ->where('estado', 'ausente')
            ->with('competencia:id,nombre')
            ->selectRaw('competencia_id, COUNT(*) as total')
            ->groupBy('competencia_id')
            ->get()
            ->map(function($item) {
                return [
                    'competencia' => $item->competencia->nombre ?? 'N/A',
                    'total' => $item->total,
                    'alerta' => $item->total >= 4 // Alerta si tiene 4 o más
                ];
            });
        $absenceSummary['por_competencia'] = $absencesByCompetence;

        // Últimas inasistencias - Optimizado
        $recentAttendances = $student->attendance_lists()
            ->where('estado', 'ausente')
            ->with('competencia:id,nombre')
            ->select('id', 'student_id', 'fecha', 'estado', 'competencia_id', 'observaciones')
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();

        // Procesos Disciplinarios Activos
        $activeDisciplinary = $student->disciplinary_actions()->count();
        
        // Llamados de atención recientes - Optimizado
        $recentDisciplinaryActions = $student->disciplinary_actions()
            ->select('id', 'student_id', 'date', 'tipo_falta', 'tipo_llamado', 'description')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        return view('student.dashboard', compact(
            'student', 
            'absenceSummary', 
            'recentAttendances', 
            'activeDisciplinary',
            'recentDisciplinaryActions'
        ));
    }
}
