<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionRa extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones_ra';
    protected $primaryKey = 'id_evaluacion';

    protected $fillable = [
        'id_matricula',
        'id_planeacion',
        'juicio_final',
        'porcentaje_aprobado',
        'porcentaje_deficiente',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_calculo' => 'datetime',
            'fecha_publicacion' => 'datetime',
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

