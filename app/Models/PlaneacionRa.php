<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaneacionRa extends Model
{
    use HasFactory;

    protected $table = 'planeacion_ra';
    protected $primaryKey = 'id_planeacion';

    protected $fillable = [
        'id_ficha',
        'id_ra',
        'id_instructor',
        'trimestre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
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

    public function ficha(): BelongsTo
    {
        return $this->belongsTo(Ficha::class, 'id_ficha', 'id_ficha');
    }

    public function resultadoAprendizaje(): BelongsTo
    {
        return $this->belongsTo(ResultadoAprendizaje::class, 'id_ra', 'id_ra');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_instructor', 'id_usuario');
    }

    public function sesiones(): HasMany
    {
        return $this->hasMany(SesionFormacion::class, 'id_planeacion', 'id_planeacion');
    }
}

