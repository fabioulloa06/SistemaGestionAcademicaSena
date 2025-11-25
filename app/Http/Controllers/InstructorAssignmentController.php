<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Competencia;
use App\Models\User;
use App\Models\CompetenciaGroupInstructor;
use Illuminate\Http\Request;

class InstructorAssignmentController extends Controller
{
    public function index()
    {
        $groups = Group::with('program.competencias')->where('activo', true)->get();
        return view('instructor_assignments.index', compact('groups'));
    }

    public function edit(Group $group)
    {
        $competencias = $group->program->competencias;
        $instructors = User::where('role', 'instructor')->get();
        
        // Obtener asignaciones actuales para este grupo
        $assignments = CompetenciaGroupInstructor::where('group_id', $group->id)
            ->get()
            ->keyBy('competencia_id');
        
        return view('instructor_assignments.edit', compact('group', 'competencias', 'instructors', 'assignments'));
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'assignments' => 'required|array',
            'assignments.*' => 'nullable|exists:users,id',
        ]);

        // Eliminar asignaciones existentes para este grupo
        CompetenciaGroupInstructor::where('group_id', $group->id)->delete();

        // Crear nuevas asignaciones
        foreach ($validated['assignments'] as $competenciaId => $instructorId) {
            if ($instructorId) {
                CompetenciaGroupInstructor::create([
                    'competencia_id' => $competenciaId,
                    'group_id' => $group->id,
                    'instructor_id' => $instructorId,
                ]);
            }
        }

        return redirect()->route('instructor_assignments.index')
            ->with('success', 'Asignaciones actualizadas correctamente.');
    }
}
