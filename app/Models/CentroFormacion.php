<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentroFormacion extends Model
{
    use HasFactory;

    protected $table = 'centros_formacion';
    protected $primaryKey = 'id_centro';

    protected $fillable = [
        'codigo_centro',
        'nombre_centro',
        'direccion',
        'ciudad',
        'departamento',
        'telefono',
        'email',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function programas(): HasMany
    {
        return $this->hasMany(ProgramaFormacion::class, 'id_centro', 'id_centro');
    }

    public function fichas(): HasMany
    {
        return $this->hasMany(Ficha::class, 'id_centro', 'id_centro');
    }
}

