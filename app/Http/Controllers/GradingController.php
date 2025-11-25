<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\LearningOutcome;
use App\Models\StudentLearningOutcome;
use App\Models\StudentCompetencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $groups = Group::with('program')->where('activo', true);
        
        // Instructores solo ven sus grupos asignados
        if ($user->isInstructor()) {
            $assignments = \App\Models\CompetenciaGroupInstructor::where('instructor_id', $user->id)->get();
            $groupIds = $assignments->pluck('group_id')->unique();
            $groups->whereIn('id', $groupIds);
        }
        
        $groups = $groups->get();
        return view('grading.index', compact('groups'));
    }

    public function grade(Group $group)
    {
        $user = auth()->user();
        
        // Verificar que el instructor tenga acceso a este grupo
        if ($user->isInstructor()) {
            $hasAccess = \App\Models\CompetenciaGroupInstructor::where('instructor_id', $user->id)
                ->where('group_id', $group->id)
                ->exists();
                
            if (!$hasAccess) {
                abort(403, 'No tienes permiso para calificar este grupo.');
            }
        }
        
        $students = $group->students;
        $competencias = $group->program->competencias()->with('learningOutcomes')->get();
        
        // Si es instructor, filtrar solo sus competencias asignadas
        if ($user->isInstructor()) {
            $assignedCompetenciaIds = \App\Models\CompetenciaGroupInstructor::where('instructor_id', $user->id)
                ->where('group_id', $group->id)
                ->pluck('competencia_id');
            $competencias = $competencias->whereIn('id', $assignedCompetenciaIds);
        }
        
        return view('grading.grade', compact('group', 'students', 'competencias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'learning_outcome_id' => 'required|exists:learning_outcomes,id',
            'estado' => 'required|in:Aprobado,No Aprobado,Pendiente',
            'observaciones' => 'nullable|string',
            'fecha_evaluacion' => 'required|date',
        ]);

        $user = auth()->user();
        $validated['instructor_id'] = $user->id;
        
        // Verificar que el instructor tenga permiso para calificar este resultado de aprendizaje
        if ($user->isInstructor()) {
            $learningOutcome = LearningOutcome::find($validated['learning_outcome_id']);
            $student = Student::find($validated['student_id']);
            
            $hasAccess = \App\Models\CompetenciaGroupInstructor::where('instructor_id', $user->id)
                ->where('group_id', $student->group_id)
                ->where('competencia_id', $learningOutcome->competencia_id)
                ->exists();
                
            if (!$hasAccess) {
                return redirect()->back()->withErrors(['error' => 'No tienes permiso para calificar este resultado de aprendizaje.']);
            }
        }

        // Create or update the grade
        StudentLearningOutcome::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'learning_outcome_id' => $validated['learning_outcome_id'],
            ],
            $validated
        );

        // Check if competencia should be updated
        $this->updateCompetenciaStatus($validated['student_id'], $validated['learning_outcome_id']);

        return redirect()->back()->with('success', 'Calificación registrada correctamente.');
    }

    public function studentProgress(Student $student)
    {
        $competencias = $student->group->program->competencias()
            ->with(['learningOutcomes.studentLearningOutcomes' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->get();

        $studentCompetencias = $student->competencias()->get()->keyBy('competencia_id');

        return view('grading.student_progress', compact('student', 'competencias', 'studentCompetencias'));
    }

    private function updateCompetenciaStatus($studentId, $learningOutcomeId)
    {
        $learningOutcome = LearningOutcome::find($learningOutcomeId);
        $competencia = $learningOutcome->competencia;
        
        // Get all learning outcomes for this competencia
        $allLearningOutcomes = $competencia->learningOutcomes;
        
        // Get student's grades for all these learning outcomes
        $studentGrades = StudentLearningOutcome::where('student_id', $studentId)
            ->whereIn('learning_outcome_id', $allLearningOutcomes->pluck('id'))
            ->get();

        // Check if all are graded
        if ($studentGrades->count() == $allLearningOutcomes->count()) {
            // Check if all are approved
            $allApproved = $studentGrades->every(function($grade) {
                return $grade->estado === 'Aprobado';
            });

            $status = $allApproved ? 'Aprobado' : 'No Aprobado';
            $fechaAprobacion = $allApproved ? now() : null;

            StudentCompetencia::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'competencia_id' => $competencia->id,
                ],
                [
                    'estado' => $status,
                    'fecha_aprobacion' => $fechaAprobacion,
                ]
            );
        } else {
            // Still in progress
            StudentCompetencia::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'competencia_id' => $competencia->id,
                ],
                [
                    'estado' => 'En Curso',
                    'fecha_aprobacion' => null,
                ]
            );
        }
    }
}
