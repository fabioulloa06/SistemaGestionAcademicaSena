<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramaFormacion extends Model
{
    use HasFactory;

    protected $table = 'programas_formacion';
    protected $primaryKey = 'id_programa';

    protected $fillable = [
        'codigo_programa',
        'nombre_programa',
        'nivel_formacion',
        'duracion_trimestres',
        'id_centro',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function centro(): BelongsTo
    {
        return $this->belongsTo(CentroFormacion::class, 'id_centro', 'id_centro');
    }

    public function competencias(): HasMany
    {
        return $this->hasMany(Competencia::class, 'id_programa', 'id_programa');
    }

    public function fichas(): HasMany
    {
        return $this->hasMany(Ficha::class, 'id_programa', 'id_programa');
    }
}

