<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LlamadoAtencion extends Model
{
    use HasFactory;

    protected $table = 'llamados_atencion';
    protected $primaryKey = 'id_llamado';

    protected $fillable = [
        'id_matricula',
        'id_tipo_falta',
        'id_reportado_por',
        'fecha_incidente',
        'descripcion_incidente',
        'evidencia_adjunta',
        'requiere_comite',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_incidente' => 'date',
            'requiere_comite' => 'boolean',
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class, 'id_matricula', 'id_matricula');
    }

    public function tipoFalta(): BelongsTo
    {
        return $this->belongsTo(TipoFalta::class, 'id_tipo_falta', 'id_tipo_falta');
    }

    public function reportadoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_reportado_por', 'id_usuario');
    }

    public function descargos(): HasMany
    {
        return $this->hasMany(Descargo::class, 'id_llamado', 'id_llamado');
    }

    public function sanciones(): HasMany
    {
        return $this->hasMany(Sancion::class, 'id_llamado', 'id_llamado');
    }
}

