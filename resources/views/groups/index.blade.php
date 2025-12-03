@extends('layouts.app')

@section('title', 'Gestión de Fichas (Grupos)')
@section('page-title', 'Gestión de Fichas')

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Listado de Fichas</h1>
            <p class="text-sm text-gray-600 mt-1">Gestiona los grupos de formación</p>
        </div>
        @if(!auth()->user()->isCoordinator())
        <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-sena-600 to-sena-700 text-white font-semibold rounded-lg shadow-md hover:from-sena-700 hover:to-sena-800 transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nueva Ficha
        </a>
        @endif
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('groups.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar por número de ficha</label>
                <div class="flex">
                    <input type="text" 
                           name="search" 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-sena-500 focus:border-sena-500 transition-colors" 
                           placeholder="Buscar ficha..."
                           value="{{ request('search') }}">
                    <button type="submit" class="px-4 py-2 bg-sena-600 text-white rounded-r-lg hover:bg-sena-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por programa</label>
                <select name="programa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sena-500 focus:border-sena-500 transition-colors">
                    <option value="">Todos los programas</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}" {{ request('programa_id') == $program->id ? 'selected' : '' }}>
                            {{ $program->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por jornada</label>
                <select name="jornada" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sena-500 focus:border-sena-500 transition-colors">
                    <option value="">Todas las jornadas</option>
                    <option value="mañana" {{ request('jornada') == 'mañana' ? 'selected' : '' }}>Mañana</option>
                    <option value="tarde" {{ request('jornada') == 'tarde' ? 'selected' : '' }}>Tarde</option>
                    <option value="noche" {{ request('jornada') == 'noche' ? 'selected' : '' }}>Noche</option>
                </select>
            </div>
            
            @if(request()->hasAny(['search', 'programa_id', 'jornada']))
                <div class="flex items-end">
                    <a href="{{ route('groups.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Limpiar
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-sena-600 to-sena-700">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Número de Ficha</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Programa</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Jornada</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Estudiantes</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($groups as $group)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $group->id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $group->numero_ficha }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $group->program->nombre ?? 'N/A' }}">
                                    {{ $group->program->nombre ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    {{ $group->jornada_formateada }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    {{ $group->students_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('groups.show', $group) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" 
                                       title="Ver detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if(!auth()->user()->isCoordinator())
                                    <a href="{{ route('groups.edit', $group) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors" 
                                       title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('groups.destroy', $group) }}" 
                                          method="POST" 
                                          class="inline"
                                          id="delete-group-{{ $group->id }}"
                                                  data-confirm-message="¿Estás seguro de eliminar este grupo?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm font-medium">No hay grupos registrados</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    @if(method_exists($groups, 'links'))
        <div class="flex justify-center">
            {{ $groups->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
