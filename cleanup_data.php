<?php

use App\Models\StudentLearningOutcome;
use App\Models\StudentCompetencia;
use App\Models\Attendance_list;
use App\Models\ImprovementPlan;
use App\Models\DisciplinaryAction;
use App\Models\Student;
use App\Models\Group;
use App\Models\LearningOutcome;
use App\Models\Competencia;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\DB;

DB::transaction(function () {
    echo "Iniciando limpieza de datos...\n";

    // 1. Eliminar resultados de aprendizaje de estudiantes
    echo "Eliminando StudentLearningOutcome...\n";
    StudentLearningOutcome::truncate();

    // 2. Eliminar competencias de estudiantes
    echo "Eliminando StudentCompetencia...\n";
    StudentCompetencia::truncate();

    // 3. Eliminar asistencias
    echo "Eliminando Attendance_list...\n";
    Attendance_list::truncate();

    // 4. Eliminar planes de mejoramiento
    echo "Eliminando ImprovementPlan...\n";
    ImprovementPlan::truncate();

    // 5. Eliminar acciones disciplinarias
    echo "Eliminando DisciplinaryAction...\n";
    DisciplinaryAction::truncate();

    // 6. Eliminar estudiantes y sus usuarios asociados
    echo "Eliminando Students y sus Users...\n";
    $students = Student::all();
    foreach ($students as $student) {
        $userId = $student->user_id;
        $student->delete();
        if ($userId) {
            User::where('id', $userId)->delete();
        }
    }

    // 7. Eliminar grupos
    echo "Eliminando Groups...\n";
    // Desactivar restricciones de clave foránea temporalmente si es necesario, pero mejor borrar en orden
    // Groups dependen de Program, Students dependen de Groups (ya borrados)
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Group::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    // 8. Eliminar resultados de aprendizaje (catálogo)
    echo "Eliminando LearningOutcome...\n";
    LearningOutcome::truncate();

    // 9. Eliminar competencias (catálogo)
    // Competencias dependen de Program
    // Competencia_Instructor pivot table needs to be cleared too?
    // Check if pivot table exists and has data
    echo "Eliminando relaciones Competencia-Instructor...\n";
    DB::table('competencia_instructor')->truncate();
    
    echo "Eliminando Competencia...\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Competencia::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    // 10. Eliminar programas
    echo "Eliminando Program...\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Program::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    echo "¡Limpieza completada exitosamente!\n";
});
