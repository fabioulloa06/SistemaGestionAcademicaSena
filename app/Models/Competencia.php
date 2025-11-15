<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competencia extends Model
{
    use HasFactory;

    protected $table = 'competencias';
    protected $primaryKey = 'id_competencia';

    protected $fillable = [
        'codigo_competencia',
        'nombre_competencia',
        'descripcion',
        'id_programa',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function programa(): BelongsTo
    {
        return $this->belongsTo(ProgramaFormacion::class, 'id_programa', 'id_programa');
    }

    public function resultadosAprendizaje(): HasMany
    {
        return $this->hasMany(ResultadoAprendizaje::class, 'id_competencia', 'id_competencia');
    }
}

