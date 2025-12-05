<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\Auditable;

class Appeal extends Model
{
    use Auditable;

    protected $fillable = [
        'administrative_act_id',
        'disciplinary_action_id',
        'student_id',
        'tipo_recurso',
        'numero_recurso',
        'fecha_presentacion',
        'motivos_recurso',
        'pruebas_aportadas',
        'archivo_adjunto',
        'estado',
        'presentado_ante',
        'resuelto_por',
        'fecha_resolucion',
        'decision',
        'motivacion_resolucion',
        'agrava_sancion',
        'observaciones',
    ];

    protected $casts = [
        'fecha_presentacion' => 'date',
        'fecha_resolucion' => 'date',
        'pruebas_aportadas' => 'array',
        'agrava_sancion' => 'boolean',
    ];

    public function administrativeAct()
    {
        return $this->belongsTo(AdministrativeAct::class);
    }

    public function disciplinaryAction()
    {
        return $this->belongsTo(DisciplinaryAction::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function presentadoAnte()
    {
        return $this->belongsTo(User::class, 'presentado_ante');
    }

    public function resueltoPor()
    {
        return $this->belongsTo(User::class, 'resuelto_por');
    }

    // Helpers
    public function puedeResolver(): bool
    {
        return $this->estado === 'en_tramite' && auth()->user()->isAdmin();
    }

    public function getTipoRecursoTextoAttribute(): string
    {
        return $this->tipo_recurso === 'reposicion' ? 'Reposición' : 'Apelación';
    }

    public function getDecisionTextoAttribute(): ?string
    {
        if (!$this->decision) {
            return null;
        }

        $decisiones = [
            'favorable' => 'Favorable',
            'desfavorable' => 'Desfavorable',
            'parcialmente_favorable' => 'Parcialmente Favorable',
        ];

        return $decisiones[$this->decision] ?? $this->decision;
    }
}
