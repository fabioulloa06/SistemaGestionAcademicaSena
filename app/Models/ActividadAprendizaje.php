<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActividadAprendizaje extends Model
{
    use HasFactory;

    protected $table = 'actividades_aprendizaje';
    protected $primaryKey = 'id_actividad';

    protected $fillable = [
        'id_planeacion',
        'id_tipo_evidencia',
        'codigo_actividad',
        'nombre_actividad',
        'descripcion',
        'porcentaje_ra',
        'fecha_limite',
        'hora_limite',
        'archivo_guia',
        'rubrica',
        'instrucciones',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_limite' => 'date',
            'hora_limite' => 'datetime',
            'rubrica' => 'array',
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function planeacion(): BelongsTo
    {
        return $this->belongsTo(PlaneacionRa::class, 'id_planeacion', 'id_planeacion');
    }

    public function tipoEvidencia(): BelongsTo
    {
        return $this->belongsTo(TipoEvidencia::class, 'id_tipo_evidencia', 'id_tipo_evidencia');
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaEvidencia::class, 'id_actividad', 'id_actividad');
    }
}

