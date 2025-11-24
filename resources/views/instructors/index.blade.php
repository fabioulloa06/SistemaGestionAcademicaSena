<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Instructores') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-person-badge-fill"></i> Lista de Instructores</h1>
        <a href="{{ route('instructors.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Crear Instructor
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($instructors as $instructor)
                <tr>
                    <td>{{ $instructor->id }}</td>
                    <td>{{ $instructor->nombre }}</td>
                    <td>{{ $instructor->documento }}</td>
                    <td>{{ $instructor->email }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('instructors.show', $instructor) }}" class="btn btn-info btn-sm" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('instructors.edit', $instructor) }}" class="btn btn-warning btn-sm" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('instructors.destroy', $instructor) }}" method="POST" style="display:inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este instructor?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Paginación si existe -->
    @if(method_exists($instructors, 'links'))
        {{ $instructors->links('pagination::custom') }}
    @endif
</x-app-layout>