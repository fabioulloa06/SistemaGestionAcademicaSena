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
            // Drop the old unique index
            // Note: The index name usually follows 'table_column_unique' convention.
            // In the create migration it was: $table->unique(['student_id', 'fecha']);
            // So default name is likely 'attendance_lists_student_id_fecha_unique'
            $table->dropUnique('attendance_lists_student_id_fecha_unique');

            // Add the new unique index including competencia_id
            $table->unique(['student_id', 'fecha', 'competencia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_lists', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'fecha', 'competencia_id']);
            $table->unique(['student_id', 'fecha'], 'attendance_lists_student_id_fecha_unique');
        });
    }
};
