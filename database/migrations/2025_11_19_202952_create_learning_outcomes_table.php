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
        if (!Schema::hasTable('learning_outcomes')) {
            Schema::create('learning_outcomes', function (Blueprint $table) {
                $table->id();
                // La foreign key se añadirá después si la tabla competencias existe
                $table->unsignedBigInteger('competencia_id');
                $table->string('codigo')->unique(); // Ej: "21010100101"
                $table->string('nombre');
                $table->text('descripcion')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
                
                // Intentar añadir foreign key solo si la tabla competencias existe
                if (Schema::hasTable('competencias')) {
                    // Verificar qué estructura tiene competencias
                    $competenciaColumns = Schema::getColumnListing('competencias');
                    if (in_array('id', $competenciaColumns)) {
                        $table->foreign('competencia_id')->references('id')->on('competencias')->onDelete('cascade');
                    } elseif (in_array('id_competencia', $competenciaColumns)) {
                        $table->foreign('competencia_id')->references('id_competencia')->on('competencias')->onDelete('cascade');
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_outcomes');
    }
};
