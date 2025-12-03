<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\Auditable;

class StudentDischarge extends Model
{
    use Auditable;

    protected $fillable = [
        'disciplinary_action_id',
        'administrative_procedure_id',
        'student_id',
        'texto_descargo',
        'pruebas_aportadas',
        'archivo_adjunto',
        'fecha_presentacion',
        'estado',
        'fecha_ampliacion',
        'solicitud_ampliacion',
        'observaciones_revision',
        'revisado_por',
        'fecha_revision',
    ];

    protected $casts = [
        'fecha_presentacion' => 'date',
        'fecha_ampliacion' => 'date',
        'fecha_revision' => 'date',
        'pruebas_aportadas' => 'array',
    ];

    public function disciplinaryAction()
    {
        return $this->belongsTo(DisciplinaryAction::class);
    }

    public function administrativeProcedure()
    {
        return $this->belongsTo(AdministrativeProcedure::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function revisadoPor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    // Helpers
    public function puedeAmpliar(): bool
    {
        return $this->estado === 'presentado' && 
               $this->administrativeProcedure && 
               $this->administrativeProcedure->puedeSolicitarAmpliacionDescargos();
    }
}
