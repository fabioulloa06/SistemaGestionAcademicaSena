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
        Schema::create('sesiones_formacion', function (Blueprint $table) {
            $table->id('id_sesion');
            $table->foreignId('id_planeacion')->constrained('planeacion_ra', 'id_planeacion')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('numero_sesion');
            $table->date('fecha_sesion');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('tema_sesion', 300)->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('lugar', ['presencial', 'virtual', 'empresa'])->default('presencial');
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['programada', 'realizada', 'cancelada'])->default('programada');
            $table->timestamps();
            
            $table->index('id_planeacion');
            $table->index('fecha_sesion');
            $table->index('estado');
            $table->unique(['id_planeacion', 'numero_sesion'], 'uk_planeacion_numero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones_formacion');
    }
};

