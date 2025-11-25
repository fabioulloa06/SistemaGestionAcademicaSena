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
        Schema::create('tipos_falta', function (Blueprint $table) {
            $table->id('id_tipo_falta');
            $table->string('codigo', 20)->unique();
            $table->string('nombre_falta', 200);
            $table->enum('categoria', ['falta_academica', 'falta_disciplinaria_leve', 'falta_disciplinaria_grave', 'falta_muy_grave']);
            $table->text('descripcion')->nullable();
            $table->string('articulo_reglamento', 100)->nullable();
            $table->string('sancion_sugerida', 200)->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamp('fecha_creacion')->useCurrent();
            
            $table->index('codigo');
            $table->index('categoria');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_falta');
    }
};

