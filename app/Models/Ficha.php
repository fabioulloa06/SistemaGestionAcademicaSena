<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ficha extends Model
{
    use HasFactory;

    protected $table = 'fichas';
    protected $primaryKey = 'id_ficha';

    protected $fillable = [
        'codigo_ficha',
        'id_programa',
        'id_centro',
        'id_coordinador',
        'id_instructor_lider',
        'fecha_inicio',
        'fecha_fin_lectiva',
        'fecha_fin_productiva',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin_lectiva' => 'date',
            'fecha_fin_productiva' => 'date',
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function programa(): BelongsTo
    {
        return $this->belongsTo(ProgramaFormacion::class, 'id_programa', 'id_programa');
    }

    public function centro(): BelongsTo
    {
        return $this->belongsTo(CentroFormacion::class, 'id_centro', 'id_centro');
    }

    public function coordinador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_coordinador', 'id_usuario');
    }

    public function instructorLider(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_instructor_lider', 'id_usuario');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'id_ficha', 'id_ficha');
    }

    public function planeaciones(): HasMany
    {
        return $this->hasMany(PlaneacionRa::class, 'id_ficha', 'id_ficha');
    }
}

