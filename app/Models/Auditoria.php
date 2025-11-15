<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditoria';
    protected $primaryKey = 'id_auditoria';

    protected $fillable = [
        'id_usuario',
        'tabla_afectada',
        'accion',
        'registro_id',
        'datos_anteriores',
        'datos_nuevos',
        'ip_origen',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'datos_anteriores' => 'array',
            'datos_nuevos' => 'array',
            'fecha_accion' => 'datetime',
        ];
    }
}

