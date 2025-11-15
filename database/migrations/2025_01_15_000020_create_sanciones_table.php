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
        Schema::create('sanciones', function (Blueprint $table) {
            $table->id('id_sancion');
            $table->foreignId('id_llamado')->constrained('llamados_atencion', 'id_llamado')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_aplicada_por')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->enum('tipo_sancion', ['amonestacion_escrita', 'plan_mejoramiento', 'condicionamiento_matricula', 'suspension_temporal', 'cancelacion_matricula']);
            $table->text('descripcion_sancion');
            $table->date('fecha_aplicacion');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['activa', 'cumplida', 'cancelada'])->default('activa');
            $table->timestamps();
            
            $table->index('id_llamado');
            $table->index('tipo_sancion');
            $table->index('estado');
            $table->index('fecha_aplicacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanciones');
    }
};

