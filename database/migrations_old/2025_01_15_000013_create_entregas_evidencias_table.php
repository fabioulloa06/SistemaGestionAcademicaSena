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
        Schema::create('entregas_evidencias', function (Blueprint $table) {
            $table->id('id_entrega');
            $table->foreignId('id_actividad')->constrained('actividades_aprendizaje', 'id_actividad')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_aprendiz')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->string('archivo_adjunto', 500)->nullable();
            $table->string('enlace_evidencia', 500)->nullable();
            $table->text('comentario_aprendiz')->nullable();
            $table->boolean('es_entrega_tardia')->default(false);
            $table->enum('estado', ['entregada', 'por_calificar', 'calificada', 'reentrega_solicitada', 'rechazada'])->default('entregada');
            $table->timestamp('fecha_entrega')->useCurrent();
            $table->date('fecha_limite_original');
            $table->timestamp('ultima_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('id_actividad');
            $table->index('id_aprendiz');
            $table->index('estado');
            $table->index('fecha_entrega');
            $table->index(['id_actividad', 'estado'], 'idx_entregas_actividad_estado');
            $table->unique(['id_actividad', 'id_aprendiz'], 'uk_actividad_aprendiz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas_evidencias');
    }
};

