<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLearningOutcome extends Model
{
    protected $fillable = [
        'student_id',
        'learning_outcome_id',
        'instructor_id',
        'estado',
        'observaciones',
        'fecha_evaluacion',
    ];

    protected $casts = [
        'fecha_evaluacion' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function learningOutcome()
    {
        return $this->belongsTo(LearningOutcome::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}
