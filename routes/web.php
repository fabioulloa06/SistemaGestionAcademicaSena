<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\DisciplinaryActionController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\LearningOutcomeController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\StudentAcademicController;
use App\Http\Controllers\ImprovementPlanController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // ===== RUTAS PARA TODOS LOS USUARIOS AUTENTICADOS =====
    Route::get('disciplinary_actions', [DisciplinaryActionController::class, 'globalIndex'])->name('disciplinary_actions.global_index');
    Route::resource('students', StudentController::class);
    
    // ===== RUTAS SOLO PARA ADMIN Y COORDINADOR =====
    Route::middleware(['role:admin,coordinator'])->group(function () {
        Route::resource('groups', GroupController::class);
        Route::resource('programs', ProgramController::class);
        Route::resource('instructors', InstructorController::class);
        
        // Asignación de Instructores a Competencias por Grupo
        Route::get('instructor-assignments', [App\Http\Controllers\InstructorAssignmentController::class, 'index'])->name('instructor_assignments.index');
        Route::get('instructor-assignments/{group}/edit', [App\Http\Controllers\InstructorAssignmentController::class, 'edit'])->name('instructor_assignments.edit');
        Route::put('instructor-assignments/{group}', [App\Http\Controllers\InstructorAssignmentController::class, 'update'])->name('instructor_assignments.update');
        
        // Competencias y Resultados de Aprendizaje (gestión)
        Route::resource('programs.competencias', CompetenciaController::class)->shallow();
        Route::get('competencias/{competencia}/assign-instructors', [CompetenciaController::class, 'assignInstructors'])->name('competencias.assign_instructors');
        Route::post('competencias/{competencia}/store-instructors', [CompetenciaController::class, 'storeInstructors'])->name('competencias.store_instructors');
        Route::resource('competencias.learning_outcomes', LearningOutcomeController::class)->shallow();
        
        // Reportes y Auditoría
        Route::get('reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/absences', [App\Http\Controllers\ReportController::class, 'absences'])->name('reports.absences');
        Route::get('reports/absences/export', [App\Http\Controllers\ReportController::class, 'exportAbsences'])->name('reports.absences.export');
        Route::get('audit', [App\Http\Controllers\AuditController::class, 'index'])->name('audit.index');
    });
    
    // ===== RUTAS SOLO PARA ADMIN E INSTRUCTOR =====
    Route::middleware(['role:admin,instructor'])->group(function () {
        // Asistencias
        Route::resource('attendance', AttendanceController::class);
        Route::get('/attendance/bulk/create', [AttendanceController::class, 'bulkCreate'])->name('attendance.bulk.create');
        Route::post('/attendance/bulk/store', [AttendanceController::class, 'bulkStore'])->name('attendance.bulk.store');
        
        // Llamados de Atención
        Route::resource('students.disciplinary_actions', DisciplinaryActionController::class)->shallow();
        Route::get('disciplinary_actions/{disciplinary_action}/print', [DisciplinaryActionController::class, 'print'])->name('disciplinary_actions.print');
        
        // Calificaciones
        Route::get('grading', [GradingController::class, 'index'])->name('grading.index');
        Route::get('grading/group/{group}', [GradingController::class, 'grade'])->name('grading.grade');
        Route::post('grading/store', [GradingController::class, 'store'])->name('grading.store');
        Route::get('grading/student/{student}', [GradingController::class, 'studentProgress'])->name('grading.student_progress');
        
        // Planes de Mejoramiento
        Route::resource('improvement_plans', ImprovementPlanController::class);
        Route::get('improvement_plans/{improvement_plan}/print', [ImprovementPlanController::class, 'print'])->name('improvement_plans.print');
    });
    
    // ===== RUTAS PARA ESTUDIANTES =====
    Route::middleware(['role:student'])->group(function () {
        Route::get('my-progress', [StudentAcademicController::class, 'myProgress'])->name('student.my_progress');
        Route::get('my-competencias', [StudentAcademicController::class, 'myCompetencias'])->name('student.my_competencias');
    });
});

// Rutas para Estudiantes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\StudentPortalController::class, 'dashboard'])->name('dashboard');
});
