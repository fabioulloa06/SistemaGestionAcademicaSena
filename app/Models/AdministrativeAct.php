<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\Auditable;

class AdministrativeAct extends Model
{
    use Auditable;

    protected $fillable = [
        'disciplinary_action_id',
        'administrative_procedure_id',
        'student_id',
        'numero_acto',
        'tipo_acto',
        'relacion_hechos',
        'relacion_descargos',
        'relacion_pruebas',
        'normas_infringidas',
        'identificacion_autores',
        'grado_responsabilidad',
        'calificacion_falta',
        'tipo_falta',
        'razones_decision',
        'referencia_recomendacion_comite',
        'tipo_sancion',
        'descripcion_sancion',
        'instructor_seguimiento_id',
        'plan_mejoramiento_designado',
        'recursos_procedentes',
        'forma_recurso',
        'plazo_recurso',
        'autoridad_recurso',
        'expedido_por',
        'fecha_expedicion',
        'fecha_notificacion',
        'metodo_notificacion',
        'notificado',
        'fecha_firmeza',
        'firme',
        'observaciones',
    ];

    protected $casts = [
        'fecha_expedicion' => 'date',
        'fecha_notificacion' => 'date',
        'fecha_firmeza' => 'date',
        'notificado' => 'boolean',
        'firme' => 'boolean',
    ];

    public function disciplinaryAction()
    {
        return $this->belongsTo(DisciplinaryAction::class);
    }

    public function administrativeProcedure()
    {
        return $this->belongsTo(AdministrativeProcedure::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function expedidoPor()
    {
        return $this->belongsTo(User::class, 'expedido_por');
    }

    public function instructorSeguimiento()
    {
        return $this->belongsTo(User::class, 'instructor_seguimiento_id');
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class);
    }

    // Helpers
    public function puedeNotificar(): bool
    {
        return !$this->notificado && $this->fecha_expedicion !== null;
    }

    public function puedeQuedarFirme(): bool
    {
        // Queda firme si no hay recursos pendientes o si han pasado los plazos
        return !$this->firme && 
               ($this->appeals()->where('estado', '!=', 'resuelto')->count() === 0 ||
                ($this->fecha_notificacion && now()->diffInDays($this->fecha_notificacion) > ($this->plazo_recurso ?? 5)));
    }

    public function getTipoSancionTextoAttribute(): string
    {
        $tipos = [
            'amonestacion_escrita' => 'Amonestación Escrita',
            'plan_mejoramiento' => 'Plan de Mejoramiento',
            'condicionamiento_matricula' => 'Condicionamiento de Matrícula',
            'suspension_temporal' => 'Suspensión Temporal',
            'cancelacion_matricula' => 'Cancelación de Matrícula',
            'ninguna' => 'No se impone sanción',
        ];

        return $tipos[$this->tipo_sancion] ?? $this->tipo_sancion;
    }
}
