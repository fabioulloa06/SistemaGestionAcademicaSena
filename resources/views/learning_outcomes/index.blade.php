<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Resultados de Aprendizaje') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-list-check"></i> Resultados de Aprendizaje</h1>
        <div>
            @if(!auth()->user()->isCoordinator())
            <a href="{{ route('competencias.learning_outcomes.create', $competencia) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo RA
            </a>
            @endif
            <a href="{{ route('programs.competencias.index', $competencia->program) }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Volver a Competencias
            </a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Competencia:</strong> {{ $competencia->codigo }} - {{ $competencia->nombre }}</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($learningOutcomes as $ra)
                    <tr>
                        <td>{{ $ra->codigo }}</td>
                        <td>{{ $ra->nombre }}</td>
                        <td>
                            <span class="badge bg-{{ $ra->activo ? 'success' : 'secondary' }}">
                                {{ $ra->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                @if(!auth()->user()->isCoordinator())
                                <a href="{{ route('learning_outcomes.edit', $ra) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('learning_outcomes.destroy', $ra) }}" method="POST" style="display:inline-block" onsubmit="return confirm('¿Estás seguro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('learning_outcomes.show', $ra) }}" class="btn btn-info btn-sm" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay resultados de aprendizaje registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $learningOutcomes->links() }}
</x-app-layout>
