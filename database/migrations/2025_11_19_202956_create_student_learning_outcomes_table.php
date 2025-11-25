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
        Schema::create('student_learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('learning_outcome_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('instructors')->onDelete('cascade');
            $table->enum('estado', ['Aprobado', 'No Aprobado', 'Pendiente'])->default('Pendiente');
            $table->text('observaciones')->nullable();
            $table->date('fecha_evaluacion');
            $table->timestamps();
            
            // Un estudiante solo puede tener una calificación por RA
            $table->unique(['student_id', 'learning_outcome_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_learning_outcomes');
    }
};
