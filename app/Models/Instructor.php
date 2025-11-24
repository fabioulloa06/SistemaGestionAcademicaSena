<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'documento',
        'email',
        'telefono',
        'especialidad',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance_list::class);
    }

    public function competencias()
    {
        return $this->belongsToMany(Competencia::class, 'competencia_instructor');
    }
}
