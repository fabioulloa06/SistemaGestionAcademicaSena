<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance_list;
use App\Models\DisciplinaryAction;
use App\Models\ImprovementPlan;
use App\Models\Student;
use App\Models\Group;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard con estadísticas
     */
    public function index()
    {
        $user = auth()->user();
        
        // Solo admin puede acceder a este dashboard
        if (!$user->isAdmin()) {
            if ($user->isCoordinator()) {
                return redirect()->route('coordinator.dashboard');
            } elseif ($user->isInstructor()) {
                return redirect()->route('instructor.dashboard');
            } elseif ($user->isStudent()) {
                return redirect()->route('student.dashboard');
            }
        }
        
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        
        // Obtener IDs de grupos accesibles (cacheado si es instructor)
        $groupIds = $user->getAccessibleGroupIds();
        
        // Filtrar grupos según rol del usuario
        $groupQuery = Group::where('activo', true);
        if ($user->isInstructor() || $user->isStudent()) {
            $groupQuery->whereIn('id', $groupIds);
        }
        
        $groups = $groupQuery->with('program')->get();
        
        // Total de estudiantes
        $studentQuery = Student::where('activo', true);
        if ($user->isInstructor() || $user->isStudent()) {
            $studentQuery->whereIn('group_id', $groupIds);
        }
        $totalStudents = $studentQuery->count();
        
        // Estadísticas de INASISTENCIAS del mes actual (optimizado con agregación en BD)
        $attendanceQuery = Attendance_list::whereBetween('fecha', [$startOfMonth, $now])
            ->where('estado', 'ausente'); // Solo contar inasistencias
            
        if ($user->isInstructor() || $user->isStudent()) {
            $attendanceQuery->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            });
        }
        
        // Contar inasistencias por competencia
        $totalInasistencias = $attendanceQuery->count();
        
        // Inasistencias consecutivas (2 o más días seguidos)
        $consecutiveAbsences = Attendance_list::whereBetween('fecha', [$startOfMonth, $now])
            ->where('estado', 'ausente')
            ->whereHas('student', function($q) use ($groupIds, $user) {
                if ($user->isInstructor() || $user->isStudent()) {
                    $q->whereIn('group_id', $groupIds);
                }
            })
            ->selectRaw('student_id, competencia_id, COUNT(*) as consecutive_count')
            ->groupBy('student_id', 'competencia_id')
            ->havingRaw('consecutive_count >= 2')
            ->count();
        
        // Inasistencias totales (4 o más por competencia)
        $totalAbsencesByCompetence = Attendance_list::whereBetween('fecha', [$startOfMonth, $now])
            ->where('estado', 'ausente')
            ->whereHas('student', function($q) use ($groupIds, $user) {
                if ($user->isInstructor() || $user->isStudent()) {
                    $q->whereIn('group_id', $groupIds);
                }
            })
            ->selectRaw('student_id, competencia_id, COUNT(*) as total_count')
            ->groupBy('student_id', 'competencia_id')
            ->havingRaw('total_count >= 4')
            ->count();
        
        $totalFallas = $totalInasistencias;
        $totalAsistencias = 0; // Ya no se cuenta asistencia, solo inasistencias
        $totalExcusas = 0;
        $totalRetardos = 0;
        
        // Si no hay grupos activos, establecer totales en 0
        $totalGroups = $groups->count();
        
        // Estadísticas de Faltas Disciplinarias (optimizado con agregación en BD)
        $disciplinaryQuery = DisciplinaryAction::query();
        if ($user->isInstructor() || $user->isStudent()) {
            $disciplinaryQuery->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            });
        }
        
        // Optimizar: usar agregación en BD
        $disciplinaryStats = $disciplinaryQuery
            ->selectRaw('tipo_falta, COUNT(*) as count')
            ->groupBy('tipo_falta')
            ->pluck('count', 'tipo_falta')
            ->toArray();
        
        $faltasAcademicas = $disciplinaryStats['Académica'] ?? 0;
        $faltasDisciplinarias = $disciplinaryStats['Disciplinaria'] ?? 0;
        
        // Estadísticas de Planes de Mejoramiento (optimizado con agregación en BD)
        $improvementPlansQuery = ImprovementPlan::query();
        if ($user->isInstructor()) {
            $improvementPlansQuery->where(function($q) use ($groupIds, $user) {
                $q->whereHas('disciplinaryAction.student', function($q2) use ($groupIds) {
                    $q2->whereIn('group_id', $groupIds);
                })->orWhere('instructor_id', $user->id);
            });
        } elseif ($user->isStudent()) {
            $improvementPlansQuery->whereHas('disciplinaryAction.student', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        // Optimizar: usar agregación en BD
        $improvementStats = $improvementPlansQuery
            ->selectRaw("
                SUM(CASE WHEN status IN ('Pendiente', 'En Progreso') THEN 1 ELSE 0 END) as abiertos,
                SUM(CASE WHEN status IN ('Cumplido', 'Incumplido') THEN 1 ELSE 0 END) as cerrados
            ")
            ->first();
        
        $planesAbiertos = $improvementStats->abiertos ?? 0;
        $planesCerrados = $improvementStats->cerrados ?? 0;
        
        // Datos para gráficos - Solo inasistencias
        $attendanceChartData = [
            'labels' => ['Inasistencias Totales', 'Consecutivas (≥2 días)', 'Por Competencia (≥4)'],
            'data' => [$totalInasistencias, $consecutiveAbsences, $totalAbsencesByCompetence],
            'colors' => ['#dc3545', '#ff6b6b', '#ee5a6f']
        ];
        
        $faltasChartData = [
            'labels' => ['Académicas', 'Disciplinarias'],
            'data' => [$faltasAcademicas, $faltasDisciplinarias],
            'colors' => ['#17a2b8', '#6f42c1']
        ];
        
        $planesChartData = [
            'labels' => ['Abiertos', 'Cerrados'],
            'data' => [$planesAbiertos, $planesCerrados],
            'colors' => ['#ffc107', '#28a745']
        ];
        
        return view('dashboard', compact(
            'totalStudents',
            'totalInasistencias',
            'consecutiveAbsences',
            'totalAbsencesByCompetence',
            'totalFallas',
            'faltasAcademicas',
            'faltasDisciplinarias',
            'planesAbiertos',
            'planesCerrados',
            'attendanceChartData',
            'faltasChartData',
            'planesChartData',
            'groups'
        ));
    }
}

