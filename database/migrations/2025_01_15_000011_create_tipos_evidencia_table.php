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
        Schema::create('tipos_evidencia', function (Blueprint $table) {
            $table->id('id_tipo_evidencia');
            $table->string('nombre', 100);
            $table->enum('tipo', ['conocimiento', 'desempeño', 'producto']);
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_evidencia');
    }
};

