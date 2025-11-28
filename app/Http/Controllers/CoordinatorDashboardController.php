<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DisciplinaryAction;
use App\Models\Student;
use App\Models\Group;
use App\Models\Program;
use Carbon\Carbon;

class CoordinatorDashboardController extends Controller
{
    /**
     * Dashboard del Coordinador - Solo revisión y vigilancia
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->isCoordinator()) {
            abort(403, 'No tienes permiso para acceder a esta vista.');
        }

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        
        // Obtener todos los programas para filtrar - Optimizado
        $programs = Program::select('id', 'nombre', 'codigo')->get();
        
        // Obtener todas las fichas activas - Optimizado
        $groups = Group::with('program:id,nombre,codigo')
            ->select('id', 'numero_ficha', 'program_id')
            ->get();
        
        // Total de estudiantes activos
        $totalStudents = Student::count();
        
        // Estadísticas de Llamados de Atención del mes
        $disciplinaryStats = DisciplinaryAction::whereBetween('date', [$startOfMonth, $now])
            ->selectRaw('tipo_falta, COUNT(*) as count')
            ->groupBy('tipo_falta')
            ->pluck('count', 'tipo_falta')
            ->toArray();
        
        $faltasAcademicas = $disciplinaryStats['Académica'] ?? 0;
        $faltasDisciplinarias = $disciplinaryStats['Disciplinaria'] ?? 0;
        
        // Llamados por tipo
        $llamadosVerbal = DisciplinaryAction::whereBetween('date', [$startOfMonth, $now])
            ->where('tipo_llamado', 'verbal')
            ->count();
        
        $llamadosEscrito = DisciplinaryAction::whereBetween('date', [$startOfMonth, $now])
            ->where('tipo_llamado', 'written')
            ->count();
        
        // Llamados por programa (top 5) - Optimizado
        $llamadosPorPrograma = DisciplinaryAction::whereBetween('date', [$startOfMonth, $now])
            ->join('students', 'disciplinary_actions.student_id', '=', 'students.id')
            ->join('groups', 'students.group_id', '=', 'groups.id')
            ->join('programs', 'groups.program_id', '=', 'programs.id')
            ->selectRaw('programs.id, programs.nombre, COUNT(*) as total')
            ->groupBy('programs.id', 'programs.nombre')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Llamados por ficha (top 5) - Optimizado
        $llamadosPorFicha = DisciplinaryAction::whereBetween('date', [$startOfMonth, $now])
            ->join('students', 'disciplinary_actions.student_id', '=', 'students.id')
            ->join('groups', 'students.group_id', '=', 'groups.id')
            ->selectRaw('groups.id, groups.numero_ficha, COUNT(*) as total')
            ->groupBy('groups.id', 'groups.numero_ficha')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Estudiantes con más llamados (top 5) - Optimizado
        $estudiantesConMasLlamados = DisciplinaryAction::whereBetween('date', [$startOfMonth, $now])
            ->join('students', 'disciplinary_actions.student_id', '=', 'students.id')
            ->selectRaw('students.id, students.nombre, students.documento, COUNT(*) as total')
            ->groupBy('students.id', 'students.nombre', 'students.documento')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Llamados recientes (últimos 10) - Optimizado con eager loading
        $llamadosRecientes = DisciplinaryAction::with(['student:id,nombre,documento,group_id', 'student.group:id,numero_ficha,program_id', 'student.group.program:id,nombre', 'disciplinaryFault:id,codigo,description', 'academicFault:id,codigo,description'])
            ->select('id', 'student_id', 'date', 'tipo_falta', 'tipo_llamado', 'description', 'disciplinary_fault_id', 'academic_fault_id')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        
        return view('coordinator.dashboard', compact(
            'totalStudents',
            'faltasAcademicas',
            'faltasDisciplinarias',
            'llamadosVerbal',
            'llamadosEscrito',
            'llamadosPorPrograma',
            'llamadosPorFicha',
            'estudiantesConMasLlamados',
            'llamadosRecientes',
            'programs',
            'groups'
        ));
    }
}

