<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Crea la tabla para los descargos del aprendiz según Acuerdo 009 de 2024
     */
    public function up(): void
    {
        Schema::create('student_discharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplinary_action_id')->constrained('disciplinary_actions')->onDelete('cascade');
            $table->foreignId('administrative_procedure_id')->nullable()->constrained('administrative_procedures')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->text('texto_descargo'); // Descargos presentados por el aprendiz
            $table->json('pruebas_aportadas')->nullable(); // Evidencias aportadas por el aprendiz
            $table->string('archivo_adjunto')->nullable(); // Ruta al archivo adjunto
            $table->date('fecha_presentacion');
            $table->enum('estado', ['presentado', 'en_revision', 'aceptado', 'rechazado', 'ampliado'])->default('presentado');
            $table->date('fecha_ampliacion')->nullable(); // Si se solicita ampliación de descargos
            $table->text('solicitud_ampliacion')->nullable(); // Texto de la solicitud de ampliación
            $table->text('observaciones_revision')->nullable(); // Observaciones del revisor
            $table->foreignId('revisado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha_revision')->nullable();
            $table->timestamps();
            
            $table->index('estado');
            $table->index('fecha_presentacion');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_discharges');
    }
};
