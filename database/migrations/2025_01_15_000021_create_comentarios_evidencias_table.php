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
        Schema::create('comentarios_evidencias', function (Blueprint $table) {
            $table->id('id_comentario');
            $table->foreignId('id_entrega')->constrained('entregas_evidencias', 'id_entrega')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->text('comentario');
            $table->foreignId('es_respuesta_a')->nullable()->constrained('comentarios_evidencias', 'id_comentario')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('fecha_comentario')->useCurrent();
            
            $table->index('id_entrega');
            $table->index('id_usuario');
            $table->index('fecha_comentario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios_evidencias');
    }
};

