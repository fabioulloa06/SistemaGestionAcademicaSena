<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\InstructorAssignmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentAcademicController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\CoordinatorDashboardController;
use App\Http\Controllers\InstructorDashboardController;

// Ruta raíz - Redirige según autenticación
Route::get('/', function () {
    if (Auth::check()) {
        // Si está autenticado, redirigir según su rol
        $user = Auth::user();
        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        } elseif ($user->isCoordinator()) {
            return redirect()->route('coordinator.dashboard');
        } elseif ($user->isInstructor()) {
            return redirect()->route('instructor.dashboard');
        } else {
            // Admin va al dashboard principal
            return redirect()->route('dashboard');
        }
    }
    // Si no está autenticado, redirigir al login
    return redirect()->route('login');
});

// Rutas de autenticación (solo login, sin registro público)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboards específicos por rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Admin
    Route::get('/coordinator/dashboard', [CoordinatorDashboardController::class, 'index'])->name('coordinator.dashboard');
    Route::get('/instructor/dashboard', [InstructorDashboardController::class, 'index'])->name('instructor.dashboard');
    
    // Rutas para registro de aprendices (solo instructor líder)
    Route::get('/aprendices', [AprendizController::class, 'index'])->name('aprendices.index');
    Route::get('/aprendices/create', [AprendizController::class, 'create'])->name('aprendices.create');
    Route::post('/aprendices', [AprendizController::class, 'store'])->name('aprendices.store');
    
    // Gestión de Programas, Grupos, Estudiantes, Instructores, Competencias (solo admin y coordinador)
    Route::middleware('permission:manage-academic-structure')->group(function () {
        Route::resource('programs', ProgramController::class);
        
        // Ruta general de competencias (redirige a programas)
        Route::get('/competencias', function () {
            // Redirigir a programas donde se pueden ver las competencias
            return redirect()->route('programs.index');
        })->name('competencias.index');
        
        // Competencias anidadas bajo Programas
        Route::prefix('programs/{program}')->name('programs.')->group(function () {
            Route::get('/competencias', [CompetenciaController::class, 'index'])->name('competencias.index');
            Route::get('/competencias/create', [CompetenciaController::class, 'create'])->name('competencias.create');
            Route::post('/competencias', [CompetenciaController::class, 'store'])->name('competencias.store');
        });
        
        // Rutas de competencias individuales (show, edit, update, destroy)
        Route::get('/competencias/{competencia}', [CompetenciaController::class, 'show'])->name('competencias.show');
        Route::get('/competencias/{competencia}/edit', [CompetenciaController::class, 'edit'])->name('competencias.edit');
        Route::put('/competencias/{competencia}', [CompetenciaController::class, 'update'])->name('competencias.update');
        Route::delete('/competencias/{competencia}', [CompetenciaController::class, 'destroy'])->name('competencias.destroy');
        Route::get('/competencias/{competencia}/assign-instructors', [CompetenciaController::class, 'assignInstructors'])->name('competencias.assign-instructors');
        Route::post('/competencias/{competencia}/assign-instructors', [CompetenciaController::class, 'storeInstructors'])->name('competencias.store-instructors');
        
        // Resultados de Aprendizaje anidados bajo Competencias
        Route::prefix('competencias/{competencia}')->name('competencias.')->group(function () {
            Route::get('/learning-outcomes', [LearningOutcomeController::class, 'index'])->name('learning_outcomes.index');
            Route::get('/learning-outcomes/create', [LearningOutcomeController::class, 'create'])->name('learning_outcomes.create');
            Route::post('/learning-outcomes', [LearningOutcomeController::class, 'store'])->name('learning_outcomes.store');
        });
        
        // Rutas de learning outcomes individuales (show, edit, update, destroy)
        Route::get('/learning-outcomes/{learning_outcome}', [LearningOutcomeController::class, 'show'])->name('learning_outcomes.show');
        Route::get('/learning-outcomes/{learning_outcome}/edit', [LearningOutcomeController::class, 'edit'])->name('learning_outcomes.edit');
        Route::put('/learning-outcomes/{learning_outcome}', [LearningOutcomeController::class, 'update'])->name('learning_outcomes.update');
        Route::delete('/learning-outcomes/{learning_outcome}', [LearningOutcomeController::class, 'destroy'])->name('learning_outcomes.destroy');
        
        // Gestión de Grupos (Fichas)
        Route::resource('groups', GroupController::class);
        
        // Gestión de Estudiantes/Aprendices
        Route::resource('students', StudentController::class);
        
        // Gestión de Instructores
        Route::resource('instructors', InstructorController::class);
        
        // Asignación de Instructores a Competencias/Grupos
        Route::resource('instructor-assignments', InstructorAssignmentController::class);
    });
    
    // Gestión de Asistencias (admin, coordinador e instructores - coordinador solo lectura)
    // Ruta para ver asistencias (todos pueden ver)
    Route::get('/attendance-lists', [AttendanceController::class, 'index'])->name('attendance-lists.index');
    
    // Rutas para gestionar asistencias (solo admin e instructores, NO coordinador)
    Route::middleware('permission:manage-attendance')->group(function () {
        Route::get('/attendance-lists/create', [AttendanceController::class, 'bulkCreate'])->name('attendance-lists.create');
        Route::post('/attendance-lists', [AttendanceController::class, 'bulkStore'])->name('attendance-lists.store');
        Route::get('/attendance-lists/{attendance_list}', [AttendanceController::class, 'show'])->name('attendance-lists.show');
        Route::get('/attendance-lists/{attendance_list}/edit', [AttendanceController::class, 'edit'])->name('attendance-lists.edit');
        Route::put('/attendance-lists/{attendance_list}', [AttendanceController::class, 'update'])->name('attendance-lists.update');
    });
    
    // Acciones Disciplinarias (Llamados de Atención) - admin, coordinador e instructores
    // Las rutas están disponibles para todos los autenticados, pero el controlador verifica permisos
    Route::get('/students/{student}/disciplinary-actions', [DisciplinaryActionController::class, 'index'])->name('students.disciplinary_actions.index');
    Route::get('/disciplinary-actions', [DisciplinaryActionController::class, 'globalIndex'])->name('disciplinary-actions.global-index');
    Route::get('/disciplinary-actions/{disciplinary_action}/print', [DisciplinaryActionController::class, 'print'])->name('disciplinary-actions.print');
    
    Route::get('/students/{student}/disciplinary-actions/create', [DisciplinaryActionController::class, 'create'])->name('students.disciplinary_actions.create');
    Route::post('/students/{student}/disciplinary-actions', [DisciplinaryActionController::class, 'store'])->name('students.disciplinary_actions.store');
    
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
