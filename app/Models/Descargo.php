<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Descargo extends Model
{
    use HasFactory;

    protected $table = 'descargos';
    protected $primaryKey = 'id_descargo';

    protected $fillable = [
        'id_llamado',
        'id_aprendiz',
        'texto_descargo',
        'evidencia_adjunta',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_presentacion' => 'datetime',
        ];
    }

    public function llamado(): BelongsTo
    {
        return $this->belongsTo(LlamadoAtencion::class, 'id_llamado', 'id_llamado');
    }

    public function aprendiz(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_aprendiz', 'id_usuario');
    }
}

