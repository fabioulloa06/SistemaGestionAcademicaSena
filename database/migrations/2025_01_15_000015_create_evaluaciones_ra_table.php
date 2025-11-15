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
        Schema::create('evaluaciones_ra', function (Blueprint $table) {
            $table->id('id_evaluacion');
            $table->foreignId('id_matricula')->constrained('matriculas', 'id_matricula')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_planeacion')->constrained('planeacion_ra', 'id_planeacion')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('juicio_final', ['A', 'D'])->comment('A=Aprobado, D=Deficiente');
            $table->decimal('porcentaje_aprobado', 5, 2)->default(0.00);
            $table->decimal('porcentaje_deficiente', 5, 2)->default(0.00);
            $table->timestamp('fecha_calculo')->useCurrent();
            $table->timestamp('fecha_publicacion')->nullable();
            $table->text('observaciones')->nullable();
            
            $table->index('id_matricula');
            $table->index('id_planeacion');
            $table->index('juicio_final');
            $table->unique(['id_matricula', 'id_planeacion'], 'uk_matricula_planeacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones_ra');
    }
};

