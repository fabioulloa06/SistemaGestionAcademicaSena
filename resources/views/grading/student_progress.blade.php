<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calificar - ') . $student->nombre }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-person-badge"></i> {{ $student->nombre }}</h1>
        <a href="{{ route('grading.grade', $student->group) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Grupo
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Documento:</strong> {{ $student->documento }}</p>
                    <p><strong>Email:</strong> {{ $student->email }}</p>
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
                                <th>Acción</th>
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
                                            <span class="badge bg-secondary">Sin Calificar</span>
                                        @endif
                                    </td>
                                    <td>{{ $grade ? $grade->fecha_evaluacion->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gradeModal{{ $ra->id }}">
                                            <i class="bi bi-pencil"></i> {{ $grade ? 'Editar' : 'Calificar' }}
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal para calificar -->
                                <div class="modal fade" id="gradeModal{{ $ra->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('grading.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                <input type="hidden" name="learning_outcome_id" value="{{ $ra->id }}">
                                                
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Calificar RA: {{ $ra->codigo }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Estado</label>
                                                        <select name="estado" class="form-select" required>
                                                            <option value="Aprobado" {{ $grade && $grade->estado == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                                            <option value="No Aprobado" {{ $grade && $grade->estado == 'No Aprobado' ? 'selected' : '' }}>No Aprobado</option>
                                                            <option value="Pendiente" {{ $grade && $grade->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Fecha de Evaluación</label>
                                                        <input type="date" name="fecha_evaluacion" class="form-control" value="{{ $grade ? $grade->fecha_evaluacion->format('Y-m-d') : date('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Observaciones</label>
                                                        <textarea name="observaciones" class="form-control" rows="3">{{ $grade ? $grade->observaciones : '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar Calificación</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
