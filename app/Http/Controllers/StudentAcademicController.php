<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAcademicController extends Controller
{
    public function myProgress()
    {
        $student = Auth::user()->student; // Assuming user has student relationship
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'No tienes un perfil de estudiante asociado.');
        }

        $competencias = $student->group->program->competencias()
            ->with(['learningOutcomes.studentLearningOutcomes' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->get();

        $studentCompetencias = $student->competencias()->get()->keyBy('competencia_id');

        return view('student.my_progress', compact('student', 'competencias', 'studentCompetencias'));
    }

    public function myCompetencias()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'No tienes un perfil de estudiante asociado.');
        }

        $competencias = $student->competencias()
            ->with('competencia.learningOutcomes')
            ->get();

        return view('student.my_competencias', compact('student', 'competencias'));
    }
}
