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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('tipo_notificacion', ['llamado_atencion', 'inasistencia', 'evaluacion', 'sancion', 'general', 'alerta_tres_faltas']);
            $table->string('titulo', 200);
            $table->text('mensaje');
            $table->string('enlace', 500)->nullable();
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->default('media');
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_lectura')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            
            $table->index('id_usuario');
            $table->index('tipo_notificacion');
            $table->index('leida');
            $table->index('fecha_creacion');
            $table->index('prioridad');
            $table->index(['id_usuario', 'leida'], 'idx_notificaciones_usuario_leida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};

