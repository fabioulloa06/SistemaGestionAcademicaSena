<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance_list;
use App\Models\DisciplinaryAction;
use App\Models\Student;
use App\Models\Group;
use App\Models\Competencia;
use App\Models\LearningOutcome;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InstructorDashboardController extends Controller
{
    /**
     * Dashboard del Instructor - Asistencias y Llamados de Atención
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->isInstructor()) {
            abort(403, 'No tienes permiso para acceder a esta vista.');
        }

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        
        // Obtener grupos asignados al instructor - Optimizado
        $groupIds = $user->getAccessibleGroupIds();
        $groups = Group::whereIn('id', $groupIds)
            ->with('program:id,nombre,codigo')
            ->select('id', 'numero_ficha', 'program_id')
            ->get();
        
        // Competencias asignadas - Optimizado
        $competenciaIds = \App\Models\CompetenciaGroupInstructor::where('instructor_id', $user->id)
            ->distinct()
            ->pluck('competencia_id');
        
        $competencias = \App\Models\Competencia::whereIn('id_competencia', $competenciaIds)
            ->select('id_competencia', 'codigo_competencia', 'nombre_competencia', 'descripcion')
            ->get();
        
        // Total de estudiantes en sus grupos - Optimizado
        $totalStudents = Student::whereIn('group_id', $groupIds)->count();
        
        // Asistencias del mes (de sus grupos)
        $attendanceStats = Attendance_list::whereBetween('fecha', [$startOfMonth, $now])
            ->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            })
            ->selectRaw('estado, COUNT(*) as count')
            ->groupBy('estado')
            ->pluck('count', 'estado')
            ->toArray();
        
        $totalAsistencias = $attendanceStats['presente'] ?? 0;
        $totalFallas = $attendanceStats['ausente'] ?? 0;
        $totalTardanzas = $attendanceStats['tarde'] ?? 0;
        
        // Llamados de atención creados por este instructor (este mes)
        $llamadosCreados = DisciplinaryAction::whereBetween('date', [$startOfMonth, $now])
            ->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            })
            ->count();
        
        // Llamados por tipo
        $llamadosAcademicos = DisciplinaryAction::whereBetween('date', [$startOfMonth, $now])
            ->where('tipo_falta', 'Académica')
            ->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            })
            ->count();
        
        $llamadosDisciplinarios = DisciplinaryAction::whereBetween('date', [$startOfMonth, $now])
            ->where('tipo_falta', 'Disciplinaria')
            ->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            })
            ->count();
        
        // Últimas asistencias registradas - Optimizado
        $ultimasAsistencias = Attendance_list::whereHas('student', function($q) use ($groupIds) {
            $q->whereIn('group_id', $groupIds);
        })
        ->with(['student:id,nombre,group_id', 'student.group:id,numero_ficha', 'competencia:id,nombre'])
        ->select('id', 'student_id', 'fecha', 'estado', 'competencia_id', 'observaciones')
        ->orderBy('fecha', 'desc')
        ->limit(5)
        ->get();
        
        // Últimos llamados de atención - Optimizado
        $ultimosLlamados = DisciplinaryAction::whereHas('student', function($q) use ($groupIds) {
            $q->whereIn('group_id', $groupIds);
        })
        ->with(['student:id,nombre,group_id', 'student.group:id,numero_ficha', 'disciplinaryFault:id,codigo,description', 'academicFault:id,codigo,description'])
        ->select('id', 'student_id', 'date', 'tipo_falta', 'tipo_llamado', 'description', 'disciplinary_fault_id', 'academic_fault_id')
        ->orderBy('date', 'desc')
        ->limit(5)
        ->get();
        
        return view('instructor.dashboard', compact(
            'groups',
            'competencias',
            'totalStudents',
            'totalAsistencias',
            'totalFallas',
            'totalTardanzas',
            'llamadosCreados',
            'llamadosAcademicos',
            'llamadosDisciplinarios',
            'ultimasAsistencias',
            'ultimosLlamados'
        ));
    }
}

