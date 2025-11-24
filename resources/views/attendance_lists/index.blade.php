<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial de Asistencias') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-calendar-check-fill"></i> Historial de Asistencias</h1>
        <a href="{{ route('attendance.bulk.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Asistencia
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Filtrar por Fecha</label>
                    <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filtrar por Grupo</label>
                    <select name="group_id" class="form-select">
                        <option value="">Todos los grupos</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->numero_ficha }} - {{ $group->program->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="bi bi-filter"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Grupo</th>
                    <th>Estudiante</th>
                    <th>Estado</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->fecha }}</td>
                        <td>{{ $attendance->student->group->numero_ficha ?? 'N/A' }}</td>
                        <td>{{ $attendance->student->nombre }}</td>
                        <td>
                            <span class="badge bg-{{ $attendance->estado == 'presente' ? 'success' : ($attendance->estado == 'ausente' ? 'danger' : 'warning') }}">
                                {{ ucfirst($attendance->estado) }}
                            </span>
                        </td>
                        <td>{{ $attendance->instructor->nombre ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay registros de asistencia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($attendances, 'links'))
        {{ $attendances->links('pagination::custom') }}
    @endif
</x-app-layout>
