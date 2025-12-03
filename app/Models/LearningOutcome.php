<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningOutcome extends Model
{
    protected $fillable = [
        'competencia_id',
        'codigo',
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'competencia_id', 'id');
    }

    public function studentLearningOutcomes()
    {
        return $this->hasMany(StudentLearningOutcome::class);
    }
}
