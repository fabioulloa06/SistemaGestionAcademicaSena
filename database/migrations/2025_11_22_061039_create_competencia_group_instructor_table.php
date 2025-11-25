<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('competencia_group_instructor')) {
            Schema::create('competencia_group_instructor', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('competencia_id');
                $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
                $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
                
                // Intentar añadir foreign key solo si la tabla competencias existe
                if (Schema::hasTable('competencias')) {
                    $competenciaColumns = Schema::getColumnListing('competencias');
                    if (in_array('id', $competenciaColumns)) {
                        $table->foreign('competencia_id')->references('id')->on('competencias')->onDelete('cascade');
                    } elseif (in_array('id_competencia', $competenciaColumns)) {
                        $table->foreign('competencia_id')->references('id_competencia')->on('competencias')->onDelete('cascade');
                    }
                }
                
                // Evitar duplicados: un instructor por competencia por grupo
                $table->unique(['competencia_id', 'group_id', 'instructor_id'], 'comp_group_instructor_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('competencia_group_instructor');
    }
};
