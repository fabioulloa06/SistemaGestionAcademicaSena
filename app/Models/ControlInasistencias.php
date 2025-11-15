<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ControlInasistencias extends Model
{
    use HasFactory;

    protected $table = 'control_inasistencias';
    protected $primaryKey = 'id_control';

    protected $fillable = [
        'id_matricula',
        'id_planeacion',
        'total_sesiones_programadas',
        'total_sesiones_asistidas',
        'total_sesiones_ausentes',
        'total_excusas_justificadas',
        'total_permisos_aprobados',
        'porcentaje_inasistencia',
        'faltas_consecutivas',
        'max_faltas_consecutivas',
        'estado_alerta',
    ];

    protected function casts(): array
    {
        return [
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class, 'id_matricula', 'id_matricula');
    }

    public function planeacion(): BelongsTo
    {
        return $this->belongsTo(PlaneacionRa::class, 'id_planeacion', 'id_planeacion');
    }
}

