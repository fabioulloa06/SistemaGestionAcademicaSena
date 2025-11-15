<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencia';

    protected $fillable = [
        'id_sesion',
        'id_aprendiz',
        'estado_asistencia',
        'hora_llegada',
        'observaciones',
        'id_registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'hora_llegada' => 'datetime',
            'fecha_registro' => 'datetime',
        ];
    }

    public function sesion(): BelongsTo
    {
        return $this->belongsTo(SesionFormacion::class, 'id_sesion', 'id_sesion');
    }

    public function aprendiz(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_aprendiz', 'id_usuario');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_registrado_por', 'id_usuario');
    }
}

