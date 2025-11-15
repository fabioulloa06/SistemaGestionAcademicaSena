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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->foreignId('id_sesion')->constrained('sesiones_formacion', 'id_sesion')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_aprendiz')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->enum('estado_asistencia', ['presente', 'ausente', 'tardanza', 'excusa_justificada', 'permiso_aprobado']);
            $table->time('hora_llegada')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->foreignId('id_registrado_por')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            
            $table->index('id_sesion');
            $table->index('id_aprendiz');
            $table->index('estado_asistencia');
            $table->index('fecha_registro');
            $table->index(['id_aprendiz', 'fecha_registro'], 'idx_asistencias_aprendiz_fecha');
            $table->unique(['id_sesion', 'id_aprendiz'], 'uk_sesion_aprendiz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};

