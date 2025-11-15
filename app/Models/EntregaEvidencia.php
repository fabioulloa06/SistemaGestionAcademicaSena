<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EntregaEvidencia extends Model
{
    use HasFactory;

    protected $table = 'entregas_evidencias';
    protected $primaryKey = 'id_entrega';

    protected $fillable = [
        'id_actividad',
        'id_aprendiz',
        'archivo_adjunto',
        'enlace_evidencia',
        'comentario_aprendiz',
        'es_entrega_tardia',
        'estado',
        'fecha_limite_original',
    ];

    protected function casts(): array
    {
        return [
            'es_entrega_tardia' => 'boolean',
            'fecha_limite_original' => 'date',
            'fecha_entrega' => 'datetime',
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(ActividadAprendizaje::class, 'id_actividad', 'id_actividad');
    }

    public function aprendiz(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_aprendiz', 'id_usuario');
    }

    public function calificacion(): HasMany
    {
        return $this->hasMany(CalificacionEvidencia::class, 'id_entrega', 'id_entrega');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioEvidencia::class, 'id_entrega', 'id_entrega');
    }
}

