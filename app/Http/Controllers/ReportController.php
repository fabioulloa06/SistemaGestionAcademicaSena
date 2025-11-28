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
        return view('reports.index');
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
