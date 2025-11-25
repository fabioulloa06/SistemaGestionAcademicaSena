<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Limpiar cache de permisos cuando se actualicen asignaciones de instructores
        \App\Models\CompetenciaGroupInstructor::saved(function ($assignment) {
            cache()->forget("user_{$assignment->instructor_id}_accessible_group_ids");
            cache()->forget("user_{$assignment->instructor_id}_competencia_assignments");
        });

        \App\Models\CompetenciaGroupInstructor::deleted(function ($assignment) {
            cache()->forget("user_{$assignment->instructor_id}_accessible_group_ids");
            cache()->forget("user_{$assignment->instructor_id}_competencia_assignments");
        });

        // Limpiar cache cuando se actualice un estudiante
        \App\Models\Student::saved(function ($student) {
            if ($student->user_id) {
                cache()->forget("user_{$student->user_id}_accessible_group_ids");
            }
        });
    }
}
