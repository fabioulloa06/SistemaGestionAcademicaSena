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
        Schema::create('control_inasistencias', function (Blueprint $table) {
            $table->id('id_control');
            $table->foreignId('id_matricula')->constrained('matriculas', 'id_matricula')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_planeacion')->constrained('planeacion_ra', 'id_planeacion')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('total_sesiones_programadas')->default(0);
            $table->integer('total_sesiones_asistidas')->default(0);
            $table->integer('total_sesiones_ausentes')->default(0);
            $table->integer('total_excusas_justificadas')->default(0);
            $table->integer('total_permisos_aprobados')->default(0);
            $table->decimal('porcentaje_inasistencia', 5, 2)->default(0.00);
            $table->integer('faltas_consecutivas')->default(0)->comment('Número de faltas consecutivas actuales');
            $table->integer('max_faltas_consecutivas')->default(0)->comment('Máximo número de faltas consecutivas alcanzado');
            $table->enum('estado_alerta', ['sin_alerta', 'preventiva', 'critica', 'causal_sancion', 'cancelacion_automatica'])->default('sin_alerta');
            $table->timestamp('fecha_ultima_actualizacion')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('fecha_creacion')->useCurrent();
            
            $table->index('id_matricula');
            $table->index('id_planeacion');
            $table->index('estado_alerta');
            $table->index('faltas_consecutivas');
            $table->unique(['id_matricula', 'id_planeacion'], 'uk_matricula_planeacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_inasistencias');
    }
};

