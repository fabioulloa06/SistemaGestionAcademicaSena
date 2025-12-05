<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendance_lists', function (Blueprint $table) {
            // Agregar learning_outcome_id para tracking de inasistencias a resultados de aprendizaje
            $table->foreignId('learning_outcome_id')->nullable()->after('competencia_id')->constrained('learning_outcomes')->onDelete('set null');
            
            // Hacer competencia_id obligatorio (ya no nullable)
            // Primero eliminar la restricción nullable si existe
            $table->foreignId('competencia_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_lists', function (Blueprint $table) {
            $table->dropForeign(['learning_outcome_id']);
            $table->dropColumn('learning_outcome_id');
            
            // Revertir competencia_id a nullable
            $table->foreignId('competencia_id')->nullable()->change();
        });
    }
};
