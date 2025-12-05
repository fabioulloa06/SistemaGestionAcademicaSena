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
        Schema::create('improvement_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplinary_action_id')->constrained()->onDelete('cascade');
            $table->text('description'); // Plan concertado
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Pendiente', 'En Progreso', 'Cumplido', 'Incumplido'])->default('Pendiente');
            $table->foreignId('instructor_id')->constrained('instructors')->onDelete('cascade'); // Responsable del seguimiento
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('improvement_plans');
    }
};
