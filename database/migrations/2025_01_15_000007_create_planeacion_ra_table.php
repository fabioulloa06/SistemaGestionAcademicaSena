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
        Schema::create('planeacion_ra', function (Blueprint $table) {
            $table->id('id_planeacion');
            $table->foreignId('id_ficha')->constrained('fichas', 'id_ficha')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_ra')->constrained('resultados_aprendizaje', 'id_ra')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_instructor')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('trimestre');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->enum('estado', ['planeada', 'en_curso', 'finalizada'])->default('planeada');
            $table->timestamps();
            
            $table->index('id_ficha');
            $table->index('id_ra');
            $table->index('id_instructor');
            $table->index('trimestre');
            $table->index('estado');
            $table->unique(['id_ficha', 'id_ra', 'trimestre'], 'uk_ficha_ra_trimestre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planeacion_ra');
    }
};

