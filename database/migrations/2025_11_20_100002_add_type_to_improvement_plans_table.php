<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('improvement_plans', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
            $table->enum('type', ['Académico', 'Disciplinario'])->default('Académico');
            // Hacer disciplinary_action_id nullable porque puede ser un plan académico general o vinculado a múltiples
            $table->foreignId('disciplinary_action_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('improvement_plans', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
            $table->dropColumn('type');
            $table->foreignId('disciplinary_action_id')->nullable(false)->change();
        });
    }
};
