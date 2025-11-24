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
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            // Cambiar 'type' de enum('verbal', 'written') a 'tipo_llamado'
            $table->renameColumn('type', 'tipo_llamado');
            
            // Agregar tipo de falta
            $table->enum('tipo_falta', ['Académica', 'Disciplinaria'])->after('student_id');
            
            // Actualizar severity para usar términos SENA
            $table->dropColumn('severity');
            $table->enum('gravedad', ['Leve', 'Grave', 'Gravísima'])->after('tipo_llamado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            $table->renameColumn('tipo_llamado', 'type');
            $table->dropColumn('tipo_falta');
            $table->dropColumn('gravedad');
            $table->string('severity')->nullable();
        });
    }
};
