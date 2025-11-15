<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComentarioEvidencia extends Model
{
    use HasFactory;

    protected $table = 'comentarios_evidencias';
    protected $primaryKey = 'id_comentario';

    protected $fillable = [
        'id_entrega',
        'id_usuario',
        'comentario',
        'es_respuesta_a',
    ];

    protected function casts(): array
    {
        return [
            'fecha_comentario' => 'datetime',
        ];
    }

    public function respuestaA(): BelongsTo
    {
        return $this->belongsTo(ComentarioEvidencia::class, 'es_respuesta_a', 'id_comentario');
    }

    public function entrega(): BelongsTo
    {
        return $this->belongsTo(EntregaEvidencia::class, 'id_entrega', 'id_entrega');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}

