<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanMejoramiento extends Model
{
    use HasFactory;

    protected $table = 'planes_mejoramiento';
    protected $primaryKey = 'id_plan';

    protected $fillable = [
        'id_evaluacion',
        'actividades',
        'recursos',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'observaciones_seguimiento',
        'id_creado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(EvaluacionRa::class, 'id_evaluacion', 'id_evaluacion');
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_creado_por', 'id_usuario');
    }
}

