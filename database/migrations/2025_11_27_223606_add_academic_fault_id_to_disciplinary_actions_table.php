<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            if (!Schema::hasColumn('disciplinary_actions', 'academic_fault_id')) {
                $table->foreignId('academic_fault_id')->nullable()->after('disciplinary_fault_id')->constrained('academic_faults')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            if (Schema::hasColumn('disciplinary_actions', 'academic_fault_id')) {
                $table->dropForeign(['academic_fault_id']);
                $table->dropColumn('academic_fault_id');
            }
        });
    }
};
