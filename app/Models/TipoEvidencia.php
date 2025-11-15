<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEvidencia extends Model
{
    use HasFactory;

    protected $table = 'tipos_evidencia';
    protected $primaryKey = 'id_tipo_evidencia';

    protected $fillable = [
        'nombre',
        'tipo',
        'descripcion',
    ];

    public function actividades(): HasMany
    {
        return $this->hasMany(ActividadAprendizaje::class, 'id_tipo_evidencia', 'id_tipo_evidencia');
    }
}

