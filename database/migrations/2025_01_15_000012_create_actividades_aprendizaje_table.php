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
        Schema::create('actividades_aprendizaje', function (Blueprint $table) {
            $table->id('id_actividad');
            $table->foreignId('id_planeacion')->constrained('planeacion_ra', 'id_planeacion')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_tipo_evidencia')->constrained('tipos_evidencia', 'id_tipo_evidencia')->onDelete('restrict')->onUpdate('cascade');
            $table->string('codigo_actividad', 50)->nullable();
            $table->string('nombre_actividad', 300);
            $table->text('descripcion')->nullable();
            $table->decimal('porcentaje_ra', 5, 2)->comment('Porcentaje que representa del RA (debe sumar 100%)');
            $table->date('fecha_limite');
            $table->time('hora_limite')->nullable();
            $table->string('archivo_guia', 500)->nullable();
            $table->json('rubrica')->nullable()->comment('Rúbrica de evaluación en formato JSON');
            $table->text('instrucciones')->nullable();
            $table->enum('estado', ['borrador', 'publicada', 'cerrada'])->default('borrador');
            $table->timestamps();
            
            $table->index('id_planeacion');
            $table->index('id_tipo_evidencia');
            $table->index('fecha_limite');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades_aprendizaje');
    }
};

