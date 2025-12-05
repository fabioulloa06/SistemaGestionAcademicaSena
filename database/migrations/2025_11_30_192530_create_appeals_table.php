<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Crea la tabla para los recursos (reposición y apelación) según Acuerdo 009 de 2024
     */
    public function up(): void
    {
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrative_act_id')->constrained('administrative_acts')->onDelete('cascade');
            $table->foreignId('disciplinary_action_id')->nullable()->constrained('disciplinary_actions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('tipo_recurso', ['reposicion', 'apelacion'])->default('reposicion');
            $table->string('numero_recurso')->unique(); // Número único del recurso
            $table->date('fecha_presentacion');
            $table->text('motivos_recurso'); // Motivos del recurso
            $table->json('pruebas_aportadas')->nullable(); // Pruebas adicionales aportadas
            $table->string('archivo_adjunto')->nullable(); // Ruta al archivo del recurso
            $table->enum('estado', ['presentado', 'en_tramite', 'resuelto', 'desistido'])->default('presentado');
            $table->foreignId('presentado_ante')->constrained('users')->onDelete('restrict'); // Autoridad ante quien se presenta
            $table->foreignId('resuelto_por')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha_resolucion')->nullable();
            $table->enum('decision', ['favorable', 'desfavorable', 'parcialmente_favorable'])->nullable();
            $table->text('motivacion_resolucion')->nullable();
            $table->boolean('agrava_sancion')->default(false); // Si agrava la sanción (no permitido)
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->index('tipo_recurso');
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
        Schema::dropIfExists('appeals');
    }
};
