<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImprovementPlan extends Model
{
    protected $fillable = [
        'student_id',
        'type',
        'disciplinary_action_id',
        'description',
        'start_date',
        'end_date',
        'status', // La tabla usa 'status', no 'estado'
        'instructor_id',
        'observations',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function disciplinaryAction()
    {
        return $this->belongsTo(DisciplinaryAction::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}
