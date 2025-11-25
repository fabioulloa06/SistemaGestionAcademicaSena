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
        Schema::create('student_competencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('competencia_id')->constrained('competencias')->onDelete('cascade');
            $table->enum('estado', ['Aprobado', 'No Aprobado', 'En Curso'])->default('En Curso');
            $table->date('fecha_aprobacion')->nullable();
            $table->timestamps();
            
            // Un estudiante solo puede tener un registro por competencia
            $table->unique(['student_id', 'competencia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_competencias');
    }
};
