<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Asistencia') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-calendar-plus-fill"></i> Registrar Asistencia</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.bulk.create') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Seleccionar Grupo</label>
                    <select name="group_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Seleccione un grupo...</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->numero_ficha }} - {{ $group->program->nombre ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if(isset($students) && $students->count() > 0)
        <form action="{{ route('attendance.bulk.store') }}" method="POST">
            @csrf
            <input type="hidden" name="group_id" value="{{ request('group_id') }}">
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Instructor</label>
                            <select name="instructor_id" class="form-select" required>
                                <option value="">Seleccione un instructor...</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}">{{ $instructor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Competencia <small class="text-muted">(Opcional)</small></label>
                            <select name="competencia_id" class="form-select">
                                <option value="">Sin competencia específica</option>
                                @foreach($competencias as $competencia)
                                    <option value="{{ $competencia->id }}">{{ $competencia->codigo }} - {{ $competencia->nombre }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Seleccione la competencia que se dictó este día</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Estudiante</th>
                            <th>Documento</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr class="{{ $student->hasAbsenceWarning() ? 'table-warning' : '' }}">
                                <td>
                                    {{ $student->nombre }}
                                    @if($student->hasAbsenceWarning())
                                        <span class="badge bg-danger" title="Alerta: 2+ Inasistencias">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $student->documento }}</td>
                                <td>
                                    <select name="attendances[{{ $student->id }}][estado]" class="form-select">
                                        <option value="presente">Presente</option>
                                        <option value="ausente">Ausente</option>
                                        <option value="tarde">Tarde</option>
                                        <option value="justificado">Justificado</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="attendances[{{ $student->id }}][observaciones]" class="form-control" placeholder="Opcional">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> Guardar Asistencia
                </button>
            </div>
        </form>
    @elseif(request('group_id'))
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i> No hay estudiantes registrados en este grupo.
        </div>
    @endif
</x-app-layout>
