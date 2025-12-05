<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            if (!Schema::hasColumn('disciplinary_actions', 'disciplinary_fault_id')) {
                $table->foreignId('disciplinary_fault_id')->nullable()->constrained('disciplinary_faults');
            }
            if (!Schema::hasColumn('disciplinary_actions', 'orientations_or_recommendations')) {
                $table->text('orientations_or_recommendations')->nullable(); // Para 2do llamado
            }
        });
    }

    public function down(): void
    {
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            $table->dropForeign(['disciplinary_fault_id']);
            $table->dropColumn('disciplinary_fault_id');
            $table->dropColumn('orientations_or_recommendations');
        });
    }
};
