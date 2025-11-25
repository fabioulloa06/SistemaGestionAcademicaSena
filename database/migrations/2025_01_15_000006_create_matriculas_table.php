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
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id('id_matricula');
            $table->foreignId('id_ficha')->constrained('fichas', 'id_ficha')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_aprendiz')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->string('numero_ficha_matricula', 50)->nullable();
            $table->date('fecha_matricula');
            $table->date('fecha_cancelacion')->nullable();
            $table->text('motivo_cancelacion')->nullable();
            $table->enum('estado', ['activa', 'cancelada', 'finalizada'])->default('activa');
            $table->timestamps();
            
            $table->index('id_ficha');
            $table->index('id_aprendiz');
            $table->index('estado');
            $table->unique(['id_ficha', 'id_aprendiz', 'estado'], 'uk_ficha_aprendiz_activa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};

