<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Competencias - ') . $program->nombre }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-award-fill"></i> Competencias del Programa</h1>
        <div>
            <a href="{{ route('programs.competencias.create', $program) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Competencia
            </a>
            <a href="{{ route('programs.index') }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Volver a Programas
            </a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Programa:</strong> {{ $program->nombre }}</p>
            <p><strong>Código:</strong> {{ $program->codigo }}</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>RAs</th>
                    <th>Instructores</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($competencias as $competencia)
                    <tr>
                        <td>{{ $competencia->codigo }}</td>
                        <td>{{ $competencia->nombre }}</td>
                        <td>
                            <a href="{{ route('competencias.learning_outcomes.index', $competencia) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-list-check"></i> {{ $competencia->learningOutcomes->count() }} RAs
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('competencias.assign_instructors', $competencia) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-person-plus"></i> {{ $competencia->instructors->count() }} Instructor(es)
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-{{ $competencia->activo ? 'success' : 'secondary' }}">
                                {{ $competencia->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('competencias.edit', $competencia) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('competencias.destroy', $competencia) }}" method="POST" style="display:inline-block" onsubmit="return confirm('¿Estás seguro?')">
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
                        <td colspan="6" class="text-center">No hay competencias registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $competencias->links() }}
</x-app-layout>
