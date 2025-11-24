<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'documento',
        'email',
        'telefono',
        'group_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function attendance_lists()
    {
        return $this->hasMany(Attendance_list::class);
    }

    public function disciplinary_actions()
    {
        return $this->hasMany(DisciplinaryAction::class);
    }

    /**
     * Check if the student has reached the absence threshold (2).
     */
    public function hasAbsenceWarning(): bool
    {
        // Count absences where status is 'ausente'
        $absences = $this->attendance_lists()->where('estado', 'ausente')->count();
        return $absences >= 2;
    }

    public function learningOutcomes()
    {
        return $this->hasMany(StudentLearningOutcome::class);
    }

    public function competencias()
    {
        return $this->hasMany(StudentCompetencia::class);
    }

    /**
     * Verificar si el estudiante tiene 3 ausencias consecutivas (cualquier competencia).
     */
    public function hasConsecutiveAbsences($days = 3): bool
    {
        $absences = $this->attendance_lists()
            ->where('estado', 'ausente')
            ->orderBy('fecha', 'desc')
            ->limit($days)
            ->get();
        
        if ($absences->count() < $days) return false;
        
        // Verificar que sean consecutivas
        $dates = $absences->pluck('fecha')->sort()->values();
        for ($i = 0; $i < $dates->count() - 1; $i++) {
            $diff = $dates[$i]->diffInDays($dates[$i + 1]);
            if ($diff > 1) return false;
        }
        return true;
    }

    /**
     * Verificar si el estudiante tiene 3 o más ausencias a la misma competencia.
     */
    public function hasAbsencesToCompetencia($competenciaId, $limit = 3): bool
    {
        $count = $this->attendance_lists()
            ->where('estado', 'ausente')
            ->where('competencia_id', $competenciaId)
            ->count();
        
        return $count >= $limit;
    }

    /**
     * Obtener el conteo de ausencias a una competencia específica.
     */
    public function getAbsencesCountToCompetencia($competenciaId): int
    {
        return $this->attendance_lists()
            ->where('estado', 'ausente')
            ->where('competencia_id', $competenciaId)
            ->count();
    }

    /**
     * Verificar si el estudiante tiene 3 ausencias consecutivas a la misma competencia.
     * Ejemplo: Falta 3 lunes seguidos a Educación Física.
     */
    public function hasConsecutiveAbsencesToCompetencia($competenciaId, $limit = 3): bool
    {
        $absences = $this->attendance_lists()
            ->where('estado', 'ausente')
            ->where('competencia_id', $competenciaId)
            ->orderBy('fecha', 'desc')
            ->limit($limit)
            ->get();
        
        if ($absences->count() < $limit) return false;
        
        // Obtener las últimas N ausencias a esta competencia
        // y verificar si son "consecutivas" (sin que haya asistido entre ellas)
        
        // Para simplificar: si tiene N ausencias a la misma competencia,
        // verificamos que no haya registros de "presente" entre la primera y última ausencia
        $firstAbsence = $absences->last()->fecha;
        $lastAbsence = $absences->first()->fecha;
        
        $presentBetween = $this->attendance_lists()
            ->where('competencia_id', $competenciaId)
            ->where('estado', 'presente')
            ->whereBetween('fecha', [$firstAbsence, $lastAbsence])
            ->count();
        
        // Si no asistió ninguna vez entre la primera y última ausencia, son consecutivas
        return $presentBetween == 0;
    }
}
