<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('improvement_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('improvement_plans', 'student_id')) {
                $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
            }
            if (!Schema::hasColumn('improvement_plans', 'type')) {
                $table->enum('type', ['Académico', 'Disciplinario'])->default('Académico');
            }
            // Hacer disciplinary_action_id nullable porque puede ser un plan académico general o vinculado a múltiples
            if (Schema::hasColumn('improvement_plans', 'disciplinary_action_id')) {
                $table->foreignId('disciplinary_action_id')->nullable()->change();
            }
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
