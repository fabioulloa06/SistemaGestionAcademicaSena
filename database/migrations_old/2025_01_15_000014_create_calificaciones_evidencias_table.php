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
        Schema::create('calificaciones_evidencias', function (Blueprint $table) {
            $table->id('id_calificacion');
            $table->foreignId('id_entrega')->unique()->constrained('entregas_evidencias', 'id_entrega')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('juicio', ['A', 'D'])->comment('A=Aprobado, D=Deficiente');
            $table->text('retroalimentacion');
            $table->text('aspectos_positivos')->nullable();
            $table->text('aspectos_mejorar')->nullable();
            $table->boolean('solicitar_reentrega')->default(false);
            $table->date('fecha_limite_reentrega')->nullable();
            $table->enum('visibilidad', ['borrador', 'publicada'])->default('borrador');
            $table->timestamp('fecha_calificacion')->useCurrent();
            $table->timestamp('fecha_publicacion')->nullable();
            $table->foreignId('id_calificado_por')->constrained('usuarios', 'id_usuario')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamp('ultima_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('juicio');
            $table->index('visibilidad');
            $table->index('fecha_calificacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones_evidencias');
    }
};

