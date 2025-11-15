<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalificacionEvidencia extends Model
{
    use HasFactory;

    protected $table = 'calificaciones_evidencias';
    protected $primaryKey = 'id_calificacion';

    protected $fillable = [
        'id_entrega',
        'juicio',
        'retroalimentacion',
        'aspectos_positivos',
        'aspectos_mejorar',
        'solicitar_reentrega',
        'fecha_limite_reentrega',
        'visibilidad',
        'id_calificado_por',
    ];

    protected function casts(): array
    {
        return [
            'solicitar_reentrega' => 'boolean',
            'fecha_limite_reentrega' => 'date',
            'fecha_calificacion' => 'datetime',
            'fecha_publicacion' => 'datetime',
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function entrega(): BelongsTo
    {
        return $this->belongsTo(EntregaEvidencia::class, 'id_entrega', 'id_entrega');
    }

    public function calificadoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_calificado_por', 'id_usuario');
    }
}

