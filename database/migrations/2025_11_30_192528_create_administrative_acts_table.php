<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Crea la tabla para los actos administrativos sancionatorios según Acuerdo 009 de 2024
     */
    public function up(): void
    {
        Schema::create('administrative_acts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplinary_action_id')->constrained('disciplinary_actions')->onDelete('cascade');
            $table->foreignId('administrative_procedure_id')->nullable()->constrained('administrative_procedures')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('numero_acto')->unique(); // Número único del acto administrativo
            $table->enum('tipo_acto', ['sancionatorio', 'no_sancionatorio'])->default('sancionatorio');
            $table->text('relacion_hechos'); // Relación sucinta de los hechos investigados
            $table->text('relacion_descargos'); // Relación de los descargos presentados
            $table->text('relacion_pruebas'); // Relación de todas las pruebas aportadas
            $table->text('normas_infringidas'); // Norma(s) infringida(s) del Reglamento
            $table->text('identificacion_autores'); // Identificación del (los) autor(es)
            $table->text('grado_responsabilidad'); // Grado de responsabilidad de cada autor
            $table->enum('calificacion_falta', ['Leve', 'Grave', 'Gravísima'])->nullable();
            $table->enum('tipo_falta', ['Académica', 'Disciplinaria', 'Académica y Disciplinaria'])->nullable();
            $table->text('razones_decision'); // Razones de la decisión adoptada
            $table->text('referencia_recomendacion_comite')->nullable(); // Referencia a la recomendación del Comité
            $table->enum('tipo_sancion', [
                'amonestacion_escrita',
                'plan_mejoramiento',
                'condicionamiento_matricula',
                'suspension_temporal',
                'cancelacion_matricula',
                'ninguna' // Si no se impone sanción
            ])->nullable();
            $table->text('descripcion_sancion')->nullable();
            $table->foreignId('instructor_seguimiento_id')->nullable()->constrained('users')->onDelete('set null'); // Si es condicionamiento, designa instructor
            $table->text('plan_mejoramiento_designado')->nullable(); // Si es condicionamiento, referencia al plan
            $table->text('recursos_procedentes')->nullable(); // Recursos que proceden contra este acto
            $table->text('forma_recurso')->nullable(); // Forma de presentar el recurso
            $table->integer('plazo_recurso')->nullable(); // Plazo en días hábiles
            $table->string('autoridad_recurso')->nullable(); // Autoridad ante quien se presenta
            $table->foreignId('expedido_por')->constrained('users')->onDelete('restrict'); // Subdirector de Centro
            $table->date('fecha_expedicion');
            $table->date('fecha_notificacion')->nullable();
            $table->enum('metodo_notificacion', ['personal', 'correo_electronico', 'aviso_domicilio', 'aviso_publico'])->nullable();
            $table->boolean('notificado')->default(false);
            $table->date('fecha_firmeza')->nullable(); // Fecha en que quedó en firme
            $table->boolean('firme')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->index('numero_acto');
            $table->index('fecha_expedicion');
            $table->index('student_id');
            $table->index('firme');
            $table->index('tipo_sancion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrative_acts');
    }
};
