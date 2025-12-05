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
        // Verificar si la tabla ya existe (puede tener estructura diferente)
        if (!Schema::hasTable('competencias')) {
            Schema::create('competencias', function (Blueprint $table) {
                $table->id();
                $table->foreignId('program_id')->constrained()->onDelete('cascade');
                $table->string('codigo')->unique(); // Ej: "210101001"
                $table->string('nombre');
                $table->text('descripcion')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competencias');
    }
};
