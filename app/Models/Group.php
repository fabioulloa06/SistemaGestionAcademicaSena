<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_ficha',
        'program_id',
        'instructor_lider_id',
        'fecha_inicio',
        'fecha_fin',
        'jornada',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance_list::class);
    }

    public function instructorLider()
    {
        return $this->belongsTo(User::class, 'instructor_lider_id');
    }
}
