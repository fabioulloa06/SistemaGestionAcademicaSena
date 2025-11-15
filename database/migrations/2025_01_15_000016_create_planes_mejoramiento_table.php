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
        Schema::create('planes_mejoramiento', function (Blueprint $table) {
            $table->id('id_plan');
            $table->foreignId('id_evaluacion')->constrained('evaluaciones_ra', 'id_evaluacion')->onDelete('cascade')->onUpdate('cascade');
            $table->text('actividades')->comment('Actividades específicas del plan');
            $table->text('recursos')->nullable()->comment('Recursos necesarios');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['activo', 'completado', 'vencido'])->default('activo');
            $table->text('observaciones_seguimiento')->nullable();
            $table->timestamps();
            $table->foreignId('id_creado_por')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            
            $table->index('id_evaluacion');
            $table->index('estado');
            $table->index('fecha_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes_mejoramiento');
    }
};

