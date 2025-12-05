<?php

namespace App\Http\Controllers;

use App\Models\Attendance_list;
use App\Models\Competencia;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = \Carbon\Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        
        // Obtener IDs de grupos accesibles
        $groupIds = $user->getAccessibleGroupIds();
        
        // ========== ESTADÍSTICAS DE INASISTENCIAS ==========
        
        // Query base para inasistencias
        $absencesQuery = Attendance_list::where('estado', 'ausente')
            ->whereBetween('fecha', [$startOfMonth, $now]);
        
        if ($user->isInstructor() || $user->isStudent()) {
            $absencesQuery->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            });
        }
        
        // Total de inasistencias del mes
        $totalInasistencias = $absencesQuery->count();
        
        // Inasistencias consecutivas (2 o más días seguidos)
        $consecutiveAbsences = Attendance_list::where('estado', 'ausente')
            ->whereBetween('fecha', [$startOfMonth, $now])
            ->whereHas('student', function($q) use ($groupIds, $user) {
                if ($user->isInstructor() || $user->isStudent()) {
                    $q->whereIn('group_id', $groupIds);
                }
            })
            ->selectRaw('student_id, competencia_id, COUNT(*) as consecutive_count')
            ->groupBy('student_id', 'competencia_id')
            ->havingRaw('consecutive_count >= 2')
            ->count();
        
        // Inasistencias por competencia (4 o más)
        $absencesByCompetence = Attendance_list::where('estado', 'ausente')
            ->whereBetween('fecha', [$startOfMonth, $now])
            ->whereHas('student', function($q) use ($groupIds, $user) {
                if ($user->isInstructor() || $user->isStudent()) {
                    $q->whereIn('group_id', $groupIds);
                }
            })
            ->selectRaw('student_id, competencia_id, COUNT(*) as total_count')
            ->groupBy('student_id', 'competencia_id')
            ->havingRaw('total_count >= 4')
            ->count();
        
        // Inasistencias por grupo
        $absencesByGroup = Attendance_list::where('estado', 'ausente')
            ->whereBetween('fecha', [$startOfMonth, $now])
            ->whereHas('student', function($q) use ($groupIds, $user) {
                if ($user->isInstructor() || $user->isStudent()) {
                    $q->whereIn('group_id', $groupIds);
                }
            })
            ->join('students', 'attendance_lists.student_id', '=', 'students.id')
            ->join('groups', 'students.group_id', '=', 'groups.id')
            ->selectRaw('groups.numero_ficha, groups.id, COUNT(*) as total')
            ->groupBy('groups.id', 'groups.numero_ficha')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Inasistencias por competencia (top 5)
        $absencesByCompetencia = Attendance_list::where('estado', 'ausente')
            ->whereBetween('fecha', [$startOfMonth, $now])
            ->whereHas('student', function($q) use ($groupIds, $user) {
                if ($user->isInstructor() || $user->isStudent()) {
                    $q->whereIn('group_id', $groupIds);
                }
            })
            ->join('competencias', 'attendance_lists.competencia_id', '=', 'competencias.id')
            ->selectRaw('competencias.nombre, competencias.id, COUNT(*) as total')
            ->groupBy('competencias.id', 'competencias.nombre')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // ========== ESTADÍSTICAS DE LLAMADOS DE ATENCIÓN ==========
        
        $disciplinaryQuery = \App\Models\DisciplinaryAction::whereBetween('date', [$startOfMonth, $now]);
        
        if ($user->isInstructor() || $user->isStudent()) {
            $disciplinaryQuery->whereHas('student', function($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            });
        }
        
        // Total de llamados de atención
        $totalLlamados = $disciplinaryQuery->count();
        
        // Llamados por tipo (Verbal/Escrito)
        $llamadosPorTipo = $disciplinaryQuery->clone()
            ->selectRaw('tipo_llamado, COUNT(*) as total')
            ->groupBy('tipo_llamado')
            ->pluck('total', 'tipo_llamado')
            ->toArray();
        
        $llamadosVerbales = $llamadosPorTipo['verbal'] ?? 0;
        $llamadosEscritos = $llamadosPorTipo['written'] ?? 0;
        
        // Llamados por tipo de falta
        $llamadosPorFalta = $disciplinaryQuery->clone()
            ->selectRaw('tipo_falta, COUNT(*) as total')
            ->groupBy('tipo_falta')
            ->pluck('total', 'tipo_falta')
            ->toArray();
        
        $faltasAcademicas = $llamadosPorFalta['Académica'] ?? 0;
        $faltasDisciplinarias = $llamadosPorFalta['Disciplinaria'] ?? 0;
        
        // Llamados por gravedad
        $llamadosPorGravedad = $disciplinaryQuery->clone()
            ->selectRaw('gravedad, COUNT(*) as total')
            ->groupBy('gravedad')
            ->pluck('total', 'gravedad')
            ->toArray();
        
        $gravedadLeve = $llamadosPorGravedad['Leve'] ?? 0;
        $gravedadGrave = $llamadosPorGravedad['Grave'] ?? 0;
        $gravedadGravísima = $llamadosPorGravedad['Gravísima'] ?? 0;
        
        // Top estudiantes con más llamados
        $topEstudiantesLlamados = \App\Models\DisciplinaryAction::whereBetween('date', [$startOfMonth, $now])
            ->whereHas('student', function($q) use ($groupIds, $user) {
                if ($user->isInstructor() || $user->isStudent()) {
                    $q->whereIn('group_id', $groupIds);
                }
            })
            ->selectRaw('student_id, COUNT(*) as total')
            ->groupBy('student_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->with('student:id,nombre,documento')
            ->get();
        
        return view('reports.index', compact(
            'totalInasistencias',
            'consecutiveAbsences',
            'absencesByCompetence',
            'absencesByGroup',
            'absencesByCompetencia',
            'totalLlamados',
            'llamadosVerbales',
            'llamadosEscritos',
            'faltasAcademicas',
            'faltasDisciplinarias',
            'gravedadLeve',
            'gravedadGrave',
            'gravedadGravísima',
            'topEstudiantesLlamados'
        ));
    }

    public function absences(Request $request)
    {
        // Optimizar: solo cargar grupos y competencias activos
        $groups = Group::where('activo', true)->with('program')->orderBy('numero_ficha')->get();
        $competencias = Competencia::where('activo', true)->orderBy('codigo')->get();
        
        // Optimizar: usar join en lugar de whereHas para mejor rendimiento
        $query = Attendance_list::with(['student.group.program', 'competencia'])
            ->join('students', 'attendance_lists.student_id', '=', 'students.id');

        if ($request->has('group_id') && $request->group_id) {
            // Optimizar: usar join en lugar de whereHas
            $query->where('students.group_id', $request->group_id);
        }

        if ($request->has('competencia_id') && $request->competencia_id) {
            $query->where('attendance_lists.competencia_id', $request->competencia_id);
        }

        if ($request->has('student_document') && $request->student_document) {
            // Optimizar: usar join en lugar de whereHas
            $query->where('students.documento', 'like', '%' . $request->student_document . '%');
        }

        if ($request->has('date_start') && $request->date_start) {
            $query->whereDate('attendance_lists.fecha', '>=', $request->date_start);
        }

        if ($request->has('date_end') && $request->date_end) {
            $query->whereDate('attendance_lists.fecha', '<=', $request->date_end);
        }

        // Solo mostrar inasistencias (ausente o justificado)
        $query->whereIn('attendance_lists.estado', ['ausente', 'justificado']);

        // Seleccionar solo columnas de attendance_lists para evitar conflictos
        $attendances = $query->select('attendance_lists.*')
            ->orderBy('attendance_lists.fecha', 'desc')
            ->orderBy('attendance_lists.id', 'desc')
            ->paginate(20);

        return view('reports.absences', compact('attendances', 'groups', 'competencias'));
    }

    public function exportAbsences(Request $request)
    {
        $fileName = 'reporte_inasistencias_' . date('Y-m-d_H-i') . '.csv';
        
        // Optimizar: usar join en lugar de whereHas
        $query = Attendance_list::with(['student.group.program', 'competencia'])
            ->join('students', 'attendance_lists.student_id', '=', 'students.id');

        // Aplicar mismos filtros optimizados
        if ($request->has('group_id') && $request->group_id) {
            $query->where('students.group_id', $request->group_id);
        }
        if ($request->has('competencia_id') && $request->competencia_id) {
            $query->where('attendance_lists.competencia_id', $request->competencia_id);
        }
        if ($request->has('student_document') && $request->student_document) {
            $query->where('students.documento', 'like', '%' . $request->student_document . '%');
        }
        if ($request->has('date_start') && $request->date_start) {
            $query->whereDate('attendance_lists.fecha', '>=', $request->date_start);
        }
        if ($request->has('date_end') && $request->date_end) {
            $query->whereDate('attendance_lists.fecha', '<=', $request->date_end);
        }
        $query->whereIn('attendance_lists.estado', ['ausente', 'justificado']);

        $attendances = $query->select('attendance_lists.*')
            ->orderBy('attendance_lists.fecha', 'desc')
            ->orderBy('attendance_lists.id', 'desc')
            ->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Fecha', 'Estudiante', 'Documento', 'Ficha', 'Competencia', 'Estado', 'Observaciones');

        $callback = function() use($attendances, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($attendances as $attendance) {
                $row['Fecha']  = $attendance->fecha->format('Y-m-d');
                $row['Estudiante'] = $attendance->student->nombre;
                $row['Documento'] = $attendance->student->documento;
                $row['Ficha'] = $attendance->student->group->numero_ficha ?? 'N/A';
                $row['Competencia'] = $attendance->competencia->nombre ?? 'N/A';
                $row['Estado'] = $attendance->estado;
                $row['Observaciones'] = $attendance->observaciones;

                fputcsv($file, array($row['Fecha'], $row['Estudiante'], $row['Documento'], $row['Ficha'], $row['Competencia'], $row['Estado'], $row['Observaciones']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
