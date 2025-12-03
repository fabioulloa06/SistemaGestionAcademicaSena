<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega la foreign key constraint para acto_administrativo_id en administrative_procedures
     */
    public function up(): void
    {
        Schema::table('administrative_procedures', function (Blueprint $table) {
            // Verificar que la columna existe antes de agregar la foreign key
            if (Schema::hasColumn('administrative_procedures', 'acto_administrativo_id')) {
                $table->foreign('acto_administrativo_id')
                    ->references('id')
                    ->on('administrative_acts')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrative_procedures', function (Blueprint $table) {
            if (Schema::hasColumn('administrative_procedures', 'acto_administrativo_id')) {
                $table->dropForeign(['acto_administrativo_id']);
            }
        });
    }
};
