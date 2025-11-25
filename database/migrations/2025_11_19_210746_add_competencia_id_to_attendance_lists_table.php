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
        Schema::table('attendance_lists', function (Blueprint $table) {
            $table->foreignId('competencia_id')->nullable()->after('instructor_id')->constrained('competencias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_lists', function (Blueprint $table) {
            $table->dropForeign(['competencia_id']);
            $table->dropColumn('competencia_id');
        });
    }
};
