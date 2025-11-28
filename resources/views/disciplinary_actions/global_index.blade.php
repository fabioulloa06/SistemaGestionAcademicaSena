<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Llamados de Atención - Global') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-exclamation-triangle-fill"></i> Historial Disciplinario Global</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Estudiante</th>
                    <th>Ficha</th>
                    <th>Tipo Falta</th>
                    <th>Tipo Llamado</th>
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
                            <a href="{{ route('students.show', $action->student) }}" class="text-decoration-none">
                                {{ $action->student->nombre }}
                            </a>
                        </td>
                        <td>{{ $action->student->group->numero_ficha ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $action->tipo_falta == 'Académica' ? 'primary' : 'warning' }}">
                                {{ $action->tipo_falta }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $action->tipo_llamado == 'verbal' ? 'info' : 'danger' }}">
                                {{ $action->tipo_llamado == 'verbal' ? 'Verbal' : 'Escrito' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $action->gravedad == 'Leve' ? 'secondary' : ($action->gravedad == 'Grave' ? 'warning' : 'dark') }}">
                                {{ $action->gravedad }}
                            </span>
                        </td>
                        <td>{{ Str::limit($action->description, 50) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('disciplinary_actions.print', $action) }}" class="btn btn-secondary btn-sm" title="Imprimir / PDF" target="_blank">
                                    <i class="bi bi-printer"></i>
                                </a>
                                @if(!auth()->user()->isCoordinator())
                                <form action="{{ route('disciplinary_actions.destroy', $action) }}" method="POST" style="display:inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este registro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay llamados de atención registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $actions->links() }}
</x-app-layout>
