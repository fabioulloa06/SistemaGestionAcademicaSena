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
        Schema::create('resultados_aprendizaje', function (Blueprint $table) {
            $table->id('id_ra');
            $table->string('codigo_ra', 50);
            $table->string('nombre_ra', 300);
            $table->text('descripcion')->nullable();
            $table->foreignId('id_competencia')->constrained('competencias', 'id_competencia')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('horas_asignadas');
            $table->integer('orden');
            $table->timestamps();
            
            $table->index('id_competencia');
            $table->index('codigo_ra');
            $table->unique(['id_competencia', 'codigo_ra'], 'uk_competencia_codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_aprendizaje');
    }
};

