<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\User;
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
        // Campos del procedimiento administrativo (Acuerdo 009 de 2024)
        'estado_procedimiento',
        'created_by',
        'investigado_por',
        'fecha_investigacion',
        'hechos_investigados',
        'comite_evaluacion_id',
        'fecha_comite',
        'recomendacion_comite',
        'administrative_act_id',
        'fecha_notificacion',
        'metodo_notificacion',
        'notificado',
    ];

    protected $casts = [
        'date' => 'date',
        'fecha_investigacion' => 'date',
        'fecha_comite' => 'date',
        'fecha_notificacion' => 'date',
        'notificado' => 'boolean',
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

    // Relaciones del procedimiento administrativo (Acuerdo 009 de 2024)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function investigadoPor()
    {
        return $this->belongsTo(User::class, 'investigado_por');
    }

    public function administrativeProcedure()
    {
        return $this->hasOne(AdministrativeProcedure::class);
    }

    public function administrativeAct()
    {
        return $this->belongsTo(AdministrativeAct::class);
    }

    public function studentDischarges()
    {
        return $this->hasMany(StudentDischarge::class);
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class);
    }

    // Helpers
    public function requiereProcedimientoAdministrativo(): bool
    {
        // Según el Acuerdo 009 de 2024, se requiere procedimiento administrativo para:
        // - Faltas graves o gravísimas
        // - Segundo llamado escrito o más
        // - Cuando se requiera sanción formal
        return $this->gravedad === 'Grave' || $this->gravedad === 'Gravísima' || 
               ($this->tipo_llamado === 'written' && $this->student->disciplinary_actions()
                   ->where('tipo_falta', $this->tipo_falta)
                   ->where('tipo_llamado', 'written')
                   ->where('id', '!=', $this->id)
                   ->count() >= 1);
    }

    public function puedePresentarDescargos(): bool
    {
        return in_array($this->estado_procedimiento, ['en_investigacion', 'descargos_presentados']);
    }

    public function puedePresentarRecurso(): bool
    {
        return $this->estado_procedimiento === 'notificado' && $this->administrativeAct && $this->administrativeAct->firme === false;
    }
}
