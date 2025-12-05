<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'duracion_meses',
        'nivel',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function competencias()
    {
        // Competencia usa program_id como foreign key
        return $this->hasMany(Competencia::class, 'program_id', 'id');
    }
}
