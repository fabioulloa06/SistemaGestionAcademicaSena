<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Crea la tabla para el procedimiento administrativo sancionatorio según Acuerdo 009 de 2024
     */
    public function up(): void
    {
        Schema::create('administrative_procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplinary_action_id')->constrained('disciplinary_actions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('tipo_falta', ['Académica', 'Disciplinaria', 'Académica y Disciplinaria'])->default('Disciplinaria');
            $table->enum('estado', ['iniciado', 'en_investigacion', 'descargos_presentados', 'en_comite', 'acto_expedido', 'notificado', 'en_recurso', 'firme'])->default('iniciado');
            $table->foreignId('iniciado_por')->constrained('users')->onDelete('restrict'); // Instructor o Coordinador
            $table->foreignId('investigado_por')->nullable()->constrained('users')->onDelete('set null'); // Subdirector de Centro o designado
            $table->date('fecha_inicio');
            $table->date('fecha_investigacion')->nullable();
            $table->text('hechos_investigados')->nullable();
            $table->text('pruebas_allegadas')->nullable(); // JSON o texto con descripción de pruebas
            $table->foreignId('comite_evaluacion_id')->nullable(); // Referencia al comité
            $table->date('fecha_comite')->nullable();
            $table->text('recomendacion_comite')->nullable();
            // La referencia a administrative_acts se agregará después de crear esa tabla
            $table->unsignedBigInteger('acto_administrativo_id')->nullable();
            $table->date('fecha_acto')->nullable();
            $table->text('motivacion_decision')->nullable(); // Razones de la decisión
            $table->boolean('apartado_recomendacion_comite')->default(false); // Si se apartó de la recomendación
            $table->text('razones_apartamiento')->nullable();
            $table->timestamps();
            
            $table->index('estado');
            $table->index('fecha_inicio');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrative_procedures');
    }
};
