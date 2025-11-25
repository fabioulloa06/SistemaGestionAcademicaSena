<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Llamados de Atención - ') . $student->nombre }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-exclamation-triangle-fill"></i> Historial Disciplinario</h1>
        <div>
            <a href="{{ route('students.disciplinary_actions.create', $student) }}" class="btn btn-danger">
                <i class="bi bi-plus-circle"></i> Nuevo Llamado de Atención
            </a>
            <a href="{{ route('students.index') }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Volver a Estudiantes
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Estudiante:</strong> {{ $student->nombre }}</p>
                    <p><strong>Documento:</strong> {{ $student->documento }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Ficha:</strong> {{ $student->group->numero_ficha ?? 'N/A' }}</p>
                    <p><strong>Programa:</strong> {{ $student->group->program->nombre ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Gravedad</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($actions as $action)
                    <tr>
                        <td>{{ $action->date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $action->tipo_llamado == 'verbal' ? 'info' : 'danger' }}">
                                {{ $action->tipo_llamado == 'verbal' ? 'Verbal' : 'Escrito' }}
                            </span>
                            <small class="d-block text-muted">{{ $action->tipo_falta }}</small>
                        </td>
                        <td>{{ $action->gravedad }}</td>
                        <td>{{ Str::limit($action->description, 50) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('disciplinary_actions.print', $action) }}" class="btn btn-secondary btn-sm" title="Imprimir / PDF" target="_blank">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <form action="{{ route('disciplinary_actions.destroy', $action) }}" method="POST" style="display:inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este registro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay llamados de atención registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $actions->links() }}
</x-app-layout>
