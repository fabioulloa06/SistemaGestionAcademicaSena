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
        Schema::create('programas_formacion', function (Blueprint $table) {
            $table->id('id_programa');
            $table->string('codigo_programa', 50)->unique();
            $table->string('nombre_programa', 200);
            $table->enum('nivel_formacion', ['técnico', 'tecnólogo', 'especialización']);
            $table->integer('duracion_trimestres');
            $table->foreignId('id_centro')->constrained('centros_formacion', 'id_centro')->onDelete('restrict')->onUpdate('cascade');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
            
            $table->index('codigo_programa');
            $table->index('id_centro');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programas_formacion');
    }
};

