<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Attendance_list extends Model
{
    use Auditable;
    use HasFactory;

    protected $fillable = [
        'student_id',
        'group_id',
        'instructor_id',
        'competencia_id',
        'fecha',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function competencia()
    {
        return $this->belongsTo(Competencia::class);
    }
}
