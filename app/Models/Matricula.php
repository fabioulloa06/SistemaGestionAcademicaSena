<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas';
    protected $primaryKey = 'id_matricula';

    protected $fillable = [
        'id_ficha',
        'id_aprendiz',
        'numero_ficha_matricula',
        'fecha_matricula',
        'fecha_cancelacion',
        'motivo_cancelacion',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_matricula' => 'date',
            'fecha_cancelacion' => 'date',
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function ficha(): BelongsTo
    {
        return $this->belongsTo(Ficha::class, 'id_ficha', 'id_ficha');
    }

    public function aprendiz(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_aprendiz', 'id_usuario');
    }
}

