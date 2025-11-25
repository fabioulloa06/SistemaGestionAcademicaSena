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
        Schema::create('fichas', function (Blueprint $table) {
            $table->id('id_ficha');
            $table->string('codigo_ficha', 50)->unique();
            $table->foreignId('id_programa')->constrained('programas_formacion', 'id_programa')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_centro')->constrained('centros_formacion', 'id_centro')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_coordinador')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_instructor_lider')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin_lectiva')->nullable();
            $table->date('fecha_fin_productiva')->nullable();
            $table->enum('estado', ['activa', 'finalizada', 'cancelada'])->default('activa');
            $table->timestamps();
            
            $table->index('codigo_ficha');
            $table->index('id_programa');
            $table->index('id_coordinador');
            $table->index('id_instructor_lider');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichas');
    }
};

