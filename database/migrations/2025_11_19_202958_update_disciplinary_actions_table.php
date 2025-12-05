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
            // Agregar tipo de falta primero
            $table->enum('tipo_falta', ['Académica', 'Disciplinaria'])->after('student_id');
            
            // Agregar nuevo campo tipo_llamado
            $table->enum('tipo_llamado', ['verbal', 'written'])->after('tipo_falta');
            
            // Agregar gravedad
            $table->enum('gravedad', ['Leve', 'Grave', 'Gravísima'])->after('tipo_llamado');
        });
        
        // Migrar datos del campo 'type' a 'tipo_llamado' usando DB raw
        if (Schema::hasColumn('disciplinary_actions', 'type')) {
            \DB::statement('UPDATE disciplinary_actions SET tipo_llamado = type WHERE tipo_llamado IS NULL');
        }
        
        // Eliminar columnas antiguas después de migrar datos
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            if (Schema::hasColumn('disciplinary_actions', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('disciplinary_actions', 'severity')) {
                $table->dropColumn('severity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            // Agregar columnas antiguas
            $table->enum('type', ['verbal', 'written'])->after('student_id');
            $table->string('severity')->nullable()->after('type');
        });
        
        // Migrar datos de vuelta
        \DB::statement('UPDATE disciplinary_actions SET type = tipo_llamado WHERE type IS NULL');
        
        // Eliminar columnas nuevas
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            $table->dropColumn('tipo_falta');
            $table->dropColumn('tipo_llamado');
            $table->dropColumn('gravedad');
        });
    }
};
