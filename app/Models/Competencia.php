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

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'program_id',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function learningOutcomes(): HasMany
    {
        return $this->hasMany(LearningOutcome::class, 'competencia_id', 'id');
    }
}

