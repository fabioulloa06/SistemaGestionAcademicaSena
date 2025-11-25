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
        Schema::create('centros_formacion', function (Blueprint $table) {
            $table->id('id_centro');
            $table->string('codigo_centro', 20)->unique();
            $table->string('nombre_centro', 200);
            $table->text('direccion')->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('departamento', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
            
            $table->index('codigo_centro');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centros_formacion');
    }
};

