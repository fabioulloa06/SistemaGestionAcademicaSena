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
        Schema::create('competencias', function (Blueprint $table) {
            $table->id('id_competencia');
            $table->string('codigo_competencia', 50);
            $table->string('nombre_competencia', 300);
            $table->text('descripcion')->nullable();
            $table->foreignId('id_programa')->constrained('programas_formacion', 'id_programa')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('orden');
            $table->timestamps();
            
            $table->index('id_programa');
            $table->index('codigo_competencia');
            $table->unique(['id_programa', 'codigo_competencia'], 'uk_programa_codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competencias');
    }
};

