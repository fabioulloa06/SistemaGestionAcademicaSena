<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoFalta extends Model
{
    use HasFactory;

    protected $table = 'tipos_falta';
    protected $primaryKey = 'id_tipo_falta';

    protected $fillable = [
        'codigo',
        'nombre_falta',
        'categoria',
        'descripcion',
        'articulo_reglamento',
        'sancion_sugerida',
    ];

    public function llamados(): HasMany
    {
        return $this->hasMany(LlamadoAtencion::class, 'id_tipo_falta', 'id_tipo_falta');
    }
}

