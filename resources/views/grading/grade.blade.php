<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calificar - Ficha ') . $group->numero_ficha }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-clipboard-check"></i> Calificar Estudiantes</h1>
        <a href="{{ route('grading.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver a Fichas
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Ficha:</strong> {{ $group->numero_ficha }}</p>
            <p><strong>Programa:</strong> {{ $group->program->nombre }}</p>
            <p><strong>Estudiantes:</strong> {{ $students->count() }}</p>
        </div>
    </div>

    @if($students->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Estudiante</th>
                        <th>Documento</th>
                        <th>Progreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->nombre }}</td>
                            <td>{{ $student->documento }}</td>
                            <td>
                                @php
                                    $totalRAs = $competencias->sum(function($c) { return $c->learningOutcomes->count(); });
                                    $gradedRAs = $student->learningOutcomes->count();
                                    $percentage = $totalRAs > 0 ? round(($gradedRAs / $totalRAs) * 100) : 0;
                                @endphp
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $percentage }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('grading.student_progress', $student) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i> Calificar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> No hay estudiantes en esta ficha.
        </div>
    @endif
</x-app-layout>
