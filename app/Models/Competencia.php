<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competencia extends Model
{
    protected $fillable = [
        'program_id',
        'codigo',
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function learningOutcomes()
    {
        return $this->hasMany(LearningOutcome::class);
    }

    public function studentCompetencias()
    {
        return $this->hasMany(StudentCompetencia::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'competencia_instructor');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance_list::class);
    }
}
