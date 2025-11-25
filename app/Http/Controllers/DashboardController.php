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
        
        // Estadísticas de Asistencias del mes actual (optimizado con eager loading)
        $attendanceQuery = Attendance_list::with(['student.group.program'])
            ->whereBetween('fecha', [$startOfMonth, $now]);
            
        if ($user->isInstructor() || $user->isStudent()) {
            $attendanceQuery->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            });
        }
        
        $attendances = $attendanceQuery->get();
        $totalAsistencias = $attendances->where('estado', 'presente')->count();
        $totalFallas = $attendances->where('estado', 'ausente')->count();
        $totalExcusas = $attendances->where('estado', 'excusa')->count();
        $totalRetardos = $attendances->where('estado', 'tardanza')->count();
        
        // Si no hay grupos activos, establecer totales en 0
        $totalGroups = $groups->count();
        
        // Estadísticas de Faltas Disciplinarias (optimizado)
        $disciplinaryQuery = DisciplinaryAction::with('student.group.program');
        if ($user->isInstructor() || $user->isStudent()) {
            $disciplinaryQuery->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            });
        }
        
        $disciplinaryActions = $disciplinaryQuery->get();
        $faltasAcademicas = $disciplinaryActions->where('tipo_falta', 'Académica')->count();
        $faltasDisciplinarias = $disciplinaryActions->where('tipo_falta', 'Disciplinaria')->count();
        
        // Estadísticas de Planes de Mejoramiento (optimizado)
        $improvementPlansQuery = ImprovementPlan::with(['disciplinaryAction.student.group']);
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
        
        $improvementPlans = $improvementPlansQuery->get();
        $planesAbiertos = $improvementPlans->whereIn('estado', ['Pendiente', 'En Progreso'])->count();
        $planesCerrados = $improvementPlans->whereIn('estado', ['Cumplido', 'Incumplido'])->count();
        
        // Datos para gráficos
        $attendanceChartData = [
            'labels' => ['Asistencias', 'Fallas', 'Excusas', 'Retardos'],
            'data' => [$totalAsistencias, $totalFallas, $totalExcusas, $totalRetardos],
            'colors' => ['#28a745', '#dc3545', '#ffc107', '#fd7e14']
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
            'totalAsistencias',
            'totalFallas',
            'totalExcusas',
            'totalRetardos',
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

