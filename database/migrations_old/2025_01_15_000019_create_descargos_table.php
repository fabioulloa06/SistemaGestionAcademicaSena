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
        Schema::create('descargos', function (Blueprint $table) {
            $table->id('id_descargo');
            $table->foreignId('id_llamado')->unique()->constrained('llamados_atencion', 'id_llamado')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_aprendiz')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->text('texto_descargo');
            $table->string('evidencia_adjunta', 500)->nullable();
            $table->timestamp('fecha_presentacion')->useCurrent();
            $table->enum('estado', ['presentado', 'en_revision', 'aceptado', 'rechazado'])->default('presentado');
            
            $table->index('estado');
            $table->index('fecha_presentacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descargos');
    }
};

