<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competencia_group_instructor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competencia_id')->constrained('competencias')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Evitar duplicados: un instructor por competencia por grupo
            $table->unique(['competencia_id', 'group_id', 'instructor_id'], 'comp_group_instructor_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competencia_group_instructor');
    }
};
