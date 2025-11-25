<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResultadoAprendizaje extends Model
{
    use HasFactory;

    protected $table = 'resultados_aprendizaje';
    protected $primaryKey = 'id_ra';

    protected $fillable = [
        'codigo_ra',
        'nombre_ra',
        'descripcion',
        'id_competencia',
        'horas_asignadas',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(Competencia::class, 'id_competencia', 'id_competencia');
    }

    public function planeaciones(): HasMany
    {
        return $this->hasMany(PlaneacionRa::class, 'id_ra', 'id_ra');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(ActividadAprendizaje::class, 'id_ra', 'id_ra');
    }
}

