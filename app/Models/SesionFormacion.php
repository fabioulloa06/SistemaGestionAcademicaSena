<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SesionFormacion extends Model
{
    use HasFactory;

    protected $table = 'sesiones_formacion';
    protected $primaryKey = 'id_sesion';

    protected $fillable = [
        'id_planeacion',
        'numero_sesion',
        'fecha_sesion',
        'hora_inicio',
        'hora_fin',
        'tema_sesion',
        'descripcion',
        'lugar',
        'observaciones',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_sesion' => 'date',
            'hora_inicio' => 'datetime',
            'hora_fin' => 'datetime',
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function planeacion(): BelongsTo
    {
        return $this->belongsTo(PlaneacionRa::class, 'id_planeacion', 'id_planeacion');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_sesion', 'id_sesion');
    }
}

