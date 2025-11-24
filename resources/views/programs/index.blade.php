<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Programas') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-book-fill"></i> Lista de Programas</h1>
        <a href="{{ route('programs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Crear Programa
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($programs as $program)
                <tr>
                    <td>{{ $program->id }}</td>
                    <td>{{ $program->nombre }}</td>
                    <td>{{ $program->descripcion }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('programs.competencias.index', $program) }}" class="btn btn-success btn-sm" title="Competencias">
                                <i class="bi bi-award"></i>
                            </a>
                            <a href="{{ route('programs.show', $program) }}" class="btn btn-info btn-sm" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('programs.edit', $program) }}" class="btn btn-warning btn-sm" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('programs.destroy', $program) }}" method="POST" style="display:inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este programa?')">
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
    @if(method_exists($programs, 'links'))
        {{ $programs->links('pagination::custom') }}
    @endif
</x-app-layout>