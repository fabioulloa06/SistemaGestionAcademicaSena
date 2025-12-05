<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Actualiza la tabla disciplinary_actions según el Acuerdo 009 de 2024
     */
    public function up(): void
    {
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            // Agregar campos para el procedimiento administrativo sancionatorio
            $table->enum('estado_procedimiento', ['inicial', 'en_investigacion', 'descargos_presentados', 'en_comite', 'acto_expedido', 'notificado', 'en_recurso', 'firme'])->default('inicial')->after('orientations_or_recommendations');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('estado_procedimiento');
            $table->foreignId('investigado_por')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
            $table->date('fecha_investigacion')->nullable()->after('investigado_por');
            $table->text('hechos_investigados')->nullable()->after('fecha_investigacion');
            $table->foreignId('comite_evaluacion_id')->nullable()->after('hechos_investigados');
            $table->date('fecha_comite')->nullable()->after('comite_evaluacion_id');
            $table->text('recomendacion_comite')->nullable()->after('fecha_comite');
            $table->foreignId('administrative_act_id')->nullable()->constrained('administrative_acts')->onDelete('set null')->after('recomendacion_comite');
            $table->date('fecha_notificacion')->nullable()->after('administrative_act_id');
            $table->enum('metodo_notificacion', ['personal', 'correo_electronico', 'aviso_domicilio', 'aviso_publico'])->nullable()->after('fecha_notificacion');
            $table->boolean('notificado')->default(false)->after('metodo_notificacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disciplinary_actions', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['investigado_por']);
            $table->dropForeign(['administrative_act_id']);
            $table->dropColumn([
                'estado_procedimiento',
                'created_by',
                'investigado_por',
                'fecha_investigacion',
                'hechos_investigados',
                'comite_evaluacion_id',
                'fecha_comite',
                'recomendacion_comite',
                'administrative_act_id',
                'fecha_notificacion',
                'metodo_notificacion',
                'notificado',
            ]);
        });
    }
};
