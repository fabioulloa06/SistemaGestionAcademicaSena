<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Progreso Académico') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-graph-up-arrow"></i> Mi Progreso Académico</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $student->nombre }}</p>
                    <p><strong>Documento:</strong> {{ $student->documento }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Ficha:</strong> {{ $student->group->numero_ficha }}</p>
                    <p><strong>Programa:</strong> {{ $student->group->program->nombre }}</p>
                </div>
            </div>
        </div>
    </div>

    @foreach($competencias as $competencia)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-award"></i> {{ $competencia->codigo }} - {{ $competencia->nombre }}
                    @if(isset($studentCompetencias[$competencia->id]))
                        <span class="badge float-end bg-{{ $studentCompetencias[$competencia->id]->estado == 'Aprobado' ? 'success' : ($studentCompetencias[$competencia->id]->estado == 'No Aprobado' ? 'danger' : 'warning') }}">
                            {{ $studentCompetencias[$competencia->id]->estado }}
                        </span>
                    @else
                        <span class="badge float-end bg-secondary">En Curso</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Código RA</th>
                                <th>Resultado de Aprendizaje</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($competencia->learningOutcomes as $ra)
                                @php
                                    $grade = $ra->studentLearningOutcomes->first();
                                @endphp
                                <tr>
                                    <td>{{ $ra->codigo }}</td>
                                    <td>{{ $ra->nombre }}</td>
                                    <td>
                                        @if($grade)
                                            <span class="badge bg-{{ $grade->estado == 'Aprobado' ? 'success' : ($grade->estado == 'No Aprobado' ? 'danger' : 'warning') }}">
                                                {{ $grade->estado }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Pendiente</span>
                                        @endif
                                    </td>
                                    <td>{{ $grade ? $grade->fecha_evaluacion->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $grade ? $grade->observaciones : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @php
                    $totalRAs = $competencia->learningOutcomes->count();
                    $approvedRAs = $competencia->learningOutcomes->filter(function($ra) {
                        $grade = $ra->studentLearningOutcomes->first();
                        return $grade && $grade->estado == 'Aprobado';
                    })->count();
                    $percentage = $totalRAs > 0 ? round(($approvedRAs / $totalRAs) * 100) : 0;
                @endphp
                
                <div class="mt-3">
                    <strong>Progreso:</strong>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $approvedRAs }}/{{ $totalRAs }} RAs Aprobados ({{ $percentage }}%)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
