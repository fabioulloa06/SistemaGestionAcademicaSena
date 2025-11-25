<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Estudiantes') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-people-fill"></i> Lista de Estudiantes</h1>
        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Crear Estudiante
        </a>
    </div>

    <!-- Formulario de búsqueda y filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Buscar por nombre, documento o email..."
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <select name="grupo_id" class="form-select">
                        <option value="">Filtrar por grupo</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" 
                                    {{ request('grupo_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->numero_ficha }} - {{ $group->program->nombre ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @if(request()->hasAny(['search', 'grupo_id']))
                    <div class="col-md-2">
                        <a href="{{ route('students.index') }}" class="btn btn-outline-danger">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Email</th>
                    <th>Grupo</th>
                    <th>Programa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>
                            {{ $student->nombre }}
                            @if($student->hasAbsenceWarning())
                                <span class="badge bg-danger" title="Alerta: 2+ Inasistencias">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                </span>
                            @endif
                        </td>
                        <td>{{ $student->documento }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->group->numero_ficha ?? 'N/A' }}</td>
                        <td>{{ $student->group->program->nombre ?? 'N/A' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('students.show', $student) }}" 
                                   class="btn btn-info btn-sm" 
                                   title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" 
                                   class="btn btn-warning btn-sm" 
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('students.disciplinary_actions.index', $student) }}" class="btn btn-danger btn-sm" title="Llamados de Atención">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" 
                                      method="POST" 
                                      style="display:inline-block"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este estudiante?')">
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
                        <td colspan="7" class="text-center text-muted">
                            <i class="bi bi-inbox"></i> No hay estudiantes registrados
                        </td>
                    </tr>
                @endforelse
        </table>
    </div>

    <!-- Paginación -->
    {{ $students->appends(request()->query())->links('pagination::custom') }}
</x-app-layout>
