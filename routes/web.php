<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AprendizController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DisciplinaryActionController;
use App\Http\Controllers\ImprovementPlanController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\LearningOutcomeController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\InstructorAssignmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentAcademicController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\AuditController;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación (solo login, sin registro público)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard y navegación
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas para registro de aprendices (solo instructor líder)
    Route::get('/aprendices', [AprendizController::class, 'index'])->name('aprendices.index');
    Route::get('/aprendices/create', [AprendizController::class, 'create'])->name('aprendices.create');
    Route::post('/aprendices', [AprendizController::class, 'store'])->name('aprendices.store');
    
    // Gestión de Programas, Grupos, Estudiantes, Instructores, Competencias (solo admin y coordinador)
    Route::middleware('permission:manage-academic-structure')->group(function () {
        Route::resource('programs', ProgramController::class);
        
        // Gestión de Grupos (Fichas)
        Route::resource('groups', GroupController::class);
        
        // Gestión de Estudiantes/Aprendices
        Route::resource('students', StudentController::class);
        
        // Gestión de Instructores
        Route::resource('instructors', InstructorController::class);
        
        // Gestión de Competencias
        Route::resource('competencias', CompetenciaController::class);
        Route::get('/competencias/{competencia}/assign-instructors', [CompetenciaController::class, 'assignInstructors'])->name('competencias.assign-instructors');
        Route::post('/competencias/{competencia}/assign-instructors', [CompetenciaController::class, 'storeInstructors'])->name('competencias.store-instructors');
        
        // Gestión de Resultados de Aprendizaje (RAs)
        Route::resource('learning-outcomes', LearningOutcomeController::class);
        
        // Asignación de Instructores a Competencias/Grupos
        Route::resource('instructor-assignments', InstructorAssignmentController::class);
    });
    
    // Gestión de Asistencias (admin, coordinador e instructores)
    Route::middleware('permission:manage-attendance')->group(function () {
        Route::get('/attendance-lists', [AttendanceController::class, 'index'])->name('attendance-lists.index');
        Route::get('/attendance-lists/create', [AttendanceController::class, 'bulkCreate'])->name('attendance-lists.create');
        Route::post('/attendance-lists', [AttendanceController::class, 'bulkStore'])->name('attendance-lists.store');
        Route::get('/attendance-lists/{attendance_list}', [AttendanceController::class, 'show'])->name('attendance-lists.show');
        Route::get('/attendance-lists/{attendance_list}/edit', [AttendanceController::class, 'edit'])->name('attendance-lists.edit');
        Route::put('/attendance-lists/{attendance_list}', [AttendanceController::class, 'update'])->name('attendance-lists.update');
    });
    
    // Gestión de Calificaciones (solo admin e instructores, NO coordinador)
    Route::middleware('permission:grade')->group(function () {
        Route::get('/grading', [GradingController::class, 'index'])->name('grading.index');
        Route::get('/grading/grade/{group}', [GradingController::class, 'grade'])->name('grading.grade');
        Route::post('/grading/store', [GradingController::class, 'store'])->name('grading.store');
        Route::get('/grading/student-progress/{student}', [GradingController::class, 'studentProgress'])->name('grading.student-progress');
    });
    
    // Acciones Disciplinarias (Llamados de Atención) - admin, coordinador e instructores
    // Acciones Disciplinarias (Llamados de Atención) - admin, coordinador e instructores
    Route::middleware('permission:view-disciplinary-actions')->group(function () {
        Route::get('/students/{student}/disciplinary-actions', [DisciplinaryActionController::class, 'index'])->name('disciplinary-actions.index');
        Route::get('/disciplinary-actions', [DisciplinaryActionController::class, 'globalIndex'])->name('disciplinary-actions.global-index');
        Route::get('/disciplinary-actions/{disciplinary_action}/print', [DisciplinaryActionController::class, 'print'])->name('disciplinary-actions.print');
    });

    Route::middleware('permission:create-disciplinary-actions')->group(function () {
        Route::get('/students/{student}/disciplinary-actions/create', [DisciplinaryActionController::class, 'create'])->name('disciplinary-actions.create');
        Route::post('/students/{student}/disciplinary-actions', [DisciplinaryActionController::class, 'store'])->name('disciplinary-actions.store');
    });
    
    // Planes de Mejoramiento
    Route::get('/improvement-plans', [ImprovementPlanController::class, 'index'])->name('improvement-plans.index');
    Route::get('/improvement-plans/create', [ImprovementPlanController::class, 'create'])->name('improvement-plans.create');
    Route::post('/improvement-plans', [ImprovementPlanController::class, 'store'])->name('improvement-plans.store');
    Route::get('/improvement-plans/{improvement_plan}', [ImprovementPlanController::class, 'show'])->name('improvement-plans.show');
    Route::get('/improvement-plans/{improvement_plan}/edit', [ImprovementPlanController::class, 'edit'])->name('improvement-plans.edit');
    Route::put('/improvement-plans/{improvement_plan}', [ImprovementPlanController::class, 'update'])->name('improvement-plans.update');
    Route::get('/improvement-plans/{improvement_plan}/print', [ImprovementPlanController::class, 'print'])->name('improvement-plans.print');
    
    // Reportes (solo admin y coordinador)
    Route::middleware('permission:view-reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/absences', [ReportController::class, 'absences'])->name('reports.absences');
    });
    
    // Auditoría (solo admin)
    Route::middleware('permission:view-audit')->group(function () {
        Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    });
    
    // Portal del Estudiante
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-progress', [StudentPortalController::class, 'myProgress'])->name('my-progress');
    });
    
    // Académico del Estudiante
    Route::get('/students/{student}/academic', [StudentAcademicController::class, 'show'])->name('students.academic');
});
