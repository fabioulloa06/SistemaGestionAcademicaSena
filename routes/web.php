<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AprendizController;

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
    
    // Rutas para registro de aprendices (solo instructor líder)
    // La verificación de permisos se hace dentro del controlador
    Route::get('/aprendices', [AprendizController::class, 'index'])->name('aprendices.index');
    Route::get('/aprendices/create', [AprendizController::class, 'create'])->name('aprendices.create');
    Route::post('/aprendices', [AprendizController::class, 'store'])->name('aprendices.store');
});
