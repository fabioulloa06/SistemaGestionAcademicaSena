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
        Schema::create('auditoria', function (Blueprint $table) {
            $table->id('id_auditoria');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->string('tabla_afectada', 100);
            $table->enum('accion', ['INSERT', 'UPDATE', 'DELETE', 'SELECT']);
            $table->integer('registro_id')->nullable();
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->string('ip_origen', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('fecha_accion')->useCurrent();
            
            $table->index('id_usuario');
            $table->index('tabla_afectada');
            $table->index('accion');
            $table->index('fecha_accion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria');
    }
};

