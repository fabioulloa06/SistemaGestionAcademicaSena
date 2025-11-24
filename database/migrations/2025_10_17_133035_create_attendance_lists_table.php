<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->nullable()->constrained()->onDelete('set null');
            $table->date('fecha');
            $table->enum('estado', ['presente', 'ausente', 'tardanza', 'excusa'])->default('ausente');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Índice único para evitar duplicados de asistencia en el mismo día
            $table->unique(['student_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_lists');
    }
};
