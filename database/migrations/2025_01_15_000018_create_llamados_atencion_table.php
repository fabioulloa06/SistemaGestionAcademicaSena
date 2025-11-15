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
        Schema::create('llamados_atencion', function (Blueprint $table) {
            $table->id('id_llamado');
            $table->foreignId('id_matricula')->constrained('matriculas', 'id_matricula')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_tipo_falta')->constrained('tipos_falta', 'id_tipo_falta')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_reportado_por')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->date('fecha_incidente');
            $table->text('descripcion_incidente');
            $table->string('evidencia_adjunta', 500)->nullable();
            $table->boolean('requiere_comite')->default(false);
            $table->enum('estado', ['reportado', 'en_descargos', 'en_revision', 'resuelto', 'archivado'])->default('reportado');
            $table->timestamps();
            
            $table->index('id_matricula');
            $table->index('id_tipo_falta');
            $table->index('estado');
            $table->index('fecha_incidente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llamados_atencion');
    }
};

