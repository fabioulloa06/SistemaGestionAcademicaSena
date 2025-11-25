<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sancion extends Model
{
    use HasFactory;

    protected $table = 'sanciones';
    protected $primaryKey = 'id_sancion';

    protected $fillable = [
        'id_llamado',
        'id_aplicada_por',
        'tipo_sancion',
        'descripcion_sancion',
        'fecha_aplicacion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_aplicacion' => 'date',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function llamado(): BelongsTo
    {
        return $this->belongsTo(LlamadoAtencion::class, 'id_llamado', 'id_llamado');
    }

    public function aplicadaPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_aplicada_por', 'id_usuario');
    }
}

