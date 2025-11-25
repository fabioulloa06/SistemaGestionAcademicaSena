<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Fichas (Grupos)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-lg font-bold">Listado de Fichas</h3>
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Nueva Ficha
                    </a>
                </div>

                <form action="{{ route('groups.index') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Buscar por número de ficha..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <select name="programa_id" class="form-select">
                            <option value="">Filtrar por programa</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" 
                                        {{ request('programa_id') == $program->id ? 'selected' : '' }}>
                                    {{ $program->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <select name="jornada" class="form-select">
                            <option value="">Filtrar por jornada</option>
                            <option value="mañana" {{ request('jornada') == 'mañana' ? 'selected' : '' }}>Mañana</option>
                            <option value="tarde" {{ request('jornada') == 'tarde' ? 'selected' : '' }}>Tarde</option>
                            <option value="noche" {{ request('jornada') == 'noche' ? 'selected' : '' }}>Noche</option>
                        </select>
                    </div>
                    
                    @if(request()->hasAny(['search', 'programa_id', 'jornada']))
                        <div class="col-md-2">
                            <a href="{{ route('groups.index') }}" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        </div>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Número de Ficha</th>
                                <th>Programa</th>
                                <th>Jornada</th>
                                <th>Estudiantes</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($groups as $group)
                                <tr>
                                    <td>{{ $group->id }}</td>
                                    <td>{{ $group->numero_ficha }}</td>
                                    <td>{{ $group->program->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $group->jornada_formateada }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $group->students_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('groups.show', $group) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('groups.edit', $group) }}" 
                                               class="btn btn-warning btn-sm" 
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('groups.destroy', $group) }}" 
                                                  method="POST" 
                                                  style="display:inline-block"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este grupo?')">
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
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="bi bi-inbox"></i> No hay grupos registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $groups->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>