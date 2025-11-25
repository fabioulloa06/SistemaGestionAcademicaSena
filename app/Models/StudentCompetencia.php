<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCompetencia extends Model
{
    protected $fillable = [
        'student_id',
        'competencia_id',
        'estado',
        'fecha_aprobacion',
    ];

    protected $casts = [
        'fecha_aprobacion' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function competencia()
    {
        return $this->belongsTo(Competencia::class);
    }
}
