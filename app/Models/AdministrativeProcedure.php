<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\Auditable;

class AdministrativeProcedure extends Model
{
    use Auditable;

    protected $fillable = [
        'disciplinary_action_id',
        'student_id',
        'tipo_falta',
        'estado',
        'iniciado_por',
        'investigado_por',
        'fecha_inicio',
        'fecha_investigacion',
        'hechos_investigados',
        'pruebas_allegadas',
        'comite_evaluacion_id',
        'fecha_comite',
        'recomendacion_comite',
        'acto_administrativo_id',
        'fecha_acto',
        'motivacion_decision',
        'apartado_recomendacion_comite',
        'razones_apartamiento',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_investigacion' => 'date',
        'fecha_comite' => 'date',
        'fecha_acto' => 'date',
        'pruebas_allegadas' => 'array',
        'apartado_recomendacion_comite' => 'boolean',
    ];

    public function disciplinaryAction()
    {
        return $this->belongsTo(DisciplinaryAction::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function iniciadoPor()
    {
        return $this->belongsTo(User::class, 'iniciado_por');
    }

    public function investigadoPor()
    {
        return $this->belongsTo(User::class, 'investigado_por');
    }

    public function administrativeAct()
    {
        return $this->belongsTo(AdministrativeAct::class, 'acto_administrativo_id');
    }

    public function studentDischarges()
    {
        return $this->hasMany(StudentDischarge::class);
    }

    // Helpers
    public function puedeSolicitarAmpliacionDescargos(): bool
    {
        return $this->estado === 'en_investigacion' && 
               $this->studentDischarges()->where('estado', 'presentado')->exists();
    }

    public function tieneDescargosPresentados(): bool
    {
        return $this->studentDischarges()->where('estado', '!=', 'rechazado')->exists();
    }
}
