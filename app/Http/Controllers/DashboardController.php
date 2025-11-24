<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance_list;
use App\Models\DisciplinaryAction;
use App\Models\ImprovementPlan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Determinar el alcance de los datos según el rol
        $studentIds = null;
        if ($user->isInstructor()) {
            // Instructores solo ven datos de estudiantes en sus grupos/competencias asignadas
            $assignments = \App\Models\CompetenciaGroupInstructor::where('instructor_id', $user->id)->get();
            
            // Obtener IDs de estudiantes de esos grupos
            $groupIds = $assignments->pluck('group_id')->unique();
            $studentIds = \App\Models\Student::whereIn('group_id', $groupIds)->pluck('id');
        }
        
        // 1. Resumen de Asistencias (Mes Actual)
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $attendanceQuery = Attendance_list::select('estado', DB::raw('count(*) as total'))
            ->whereMonth('fecha', $currentMonth)
            ->whereYear('fecha', $currentYear);
            
        if ($user->isInstructor() && $studentIds) {
            $attendanceQuery->whereIn('student_id', $studentIds);
        }
        
        $attendanceStats = $attendanceQuery->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        // Asegurar que existan todas las claves para el gráfico
        $attendanceData = [
            'Asistió' => $attendanceStats['presente'] ?? 0,
            'Falla' => $attendanceStats['ausente'] ?? 0,
            'Excusa' => $attendanceStats['justificado'] ?? 0,
            'Retardo' => $attendanceStats['tarde'] ?? 0,
        ];

        // 2. Faltas Académicas vs Disciplinarias (Total Histórico)
        $faultQuery = DisciplinaryAction::select('tipo_falta', DB::raw('count(*) as total'));
        
        if ($user->isInstructor() && $studentIds) {
            $faultQuery->whereIn('student_id', $studentIds);
        }
        
        $faultStats = $faultQuery->groupBy('tipo_falta')
            ->pluck('total', 'tipo_falta')
            ->toArray();

        $faultData = [
            'Académica' => $faultStats['Académica'] ?? 0,
            'Disciplinaria' => $faultStats['Disciplinaria'] ?? 0,
        ];

        // 3. Estado de Planes de Mejoramiento
        $planQuery = ImprovementPlan::select('status', DB::raw('count(*) as total'));
        
        if ($user->isInstructor() && $studentIds) {
            $planQuery->whereHas('disciplinaryAction', function ($query) use ($studentIds) {
                $query->whereIn('student_id', $studentIds);
            });
        }
        
        $planStats = $planQuery->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Agrupar en Abiertos vs Cerrados
        $planData = [
            'Abiertos' => ($planStats['Pendiente'] ?? 0) + ($planStats['En Progreso'] ?? 0),
            'Cerrados' => ($planStats['Cumplido'] ?? 0) + ($planStats['Incumplido'] ?? 0),
        ];

        // 4. Alertas de Estudiantes en Riesgo
        $atRiskQuery = \App\Models\Student::whereHas('attendance_lists', function ($query) {
            $query->where('estado', 'ausente');
        }, '>=', 3);
        
        if ($user->isInstructor() && $studentIds) {
            $atRiskQuery->whereIn('id', $studentIds);
        }
        
        $atRiskStudents = $atRiskQuery->withCount(['attendance_lists' => function ($query) {
            $query->where('estado', 'ausente');
        }])->get();

        // Estudiantes con 3 inasistencias consecutivas
        $consecutiveQuery = \App\Models\Student::whereHas('attendance_lists', function ($query) {
            $query->where('estado', 'ausente');
        });
        
        if ($user->isInstructor() && $studentIds) {
            $consecutiveQuery->whereIn('id', $studentIds);
        }
        
        $consecutiveAbsencesStudents = $consecutiveQuery->get()->filter(function ($student) {
            $lastThree = $student->attendance_lists()->orderBy('fecha', 'desc')->take(3)->get();
            if ($lastThree->count() < 3) return false;
            
            foreach ($lastThree as $attendance) {
                if ($attendance->estado !== 'ausente') return false;
            }
            return true;
        });

        return view('dashboard', compact('attendanceData', 'faultData', 'planData', 'atRiskStudents', 'consecutiveAbsencesStudents'));
    }
}
