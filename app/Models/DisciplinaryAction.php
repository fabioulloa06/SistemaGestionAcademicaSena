<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Traits\Auditable;

class DisciplinaryAction extends Model
{
    use Auditable;
    protected $fillable = [
        'student_id',
        'tipo_falta',
        'tipo_llamado',
        'gravedad',
        'description',
        'date',
        'disciplinary_fault_id',
        'academic_fault_id',
        'orientations_or_recommendations',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function disciplinaryFault()
    {
        return $this->belongsTo(DisciplinaryFault::class);
    }

    public function academicFault()
    {
        return $this->belongsTo(AcademicFault::class);
    }

    public function improvementPlan()
    {
        return $this->hasOne(ImprovementPlan::class);
    }
}
