<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Ruta para obtener resultados de aprendizaje de una competencia
Route::get('/learning-outcomes', function (Request $request) {
    $competenciaId = $request->query('competencia_id');
    
    if (!$competenciaId) {
        return response()->json([]);
    }
    
    $learningOutcomes = \App\Models\LearningOutcome::where('competencia_id', $competenciaId)
        ->where('activo', true)
        ->select('id', 'codigo', 'nombre')
        ->get();
    
    return response()->json($learningOutcomes);
})->middleware('auth:sanctum');
