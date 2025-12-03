@extends('layouts.app')

@section('title', 'Estudiantes')
@section('page-title', 'Estudiantes')

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Estudiantes</h1>
            <div class="flex items-center gap-4 mt-2">
                <a href="{{ route('students.index') }}" class="text-sm text-sena-600 hover:text-sena-700 font-medium">Lista de Estudiantes</a>
                @if(!auth()->user()->isCoordinator())
                <a href="{{ route('students.create') }}" class="text-sm text-gray-600 hover:text-gray-700 font-medium">Crear Estudiante</a>
                @endif
            </div>
        </div>
        @if(!auth()->user()->isCoordinator())
        <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-sena-600 to-sena-700 text-white font-semibold rounded-lg shadow-md hover:from-sena-700 hover:to-sena-800 transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Crear Estudiante
        </a>
        @endif
    </div>

    <!-- Formulario de búsqueda y filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar por nombre, documento o email</label>
                <div class="flex">
                    <input type="text" 
                           name="search" 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-sena-500 focus:border-sena-500 transition-colors" 
                           placeholder="Buscar estudiantes..."
                           value="{{ request('search') }}">
                    <button type="submit" class="px-4 py-2 bg-sena-600 text-white rounded-r-lg hover:bg-sena-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por grupo</label>
                <select name="grupo_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sena-500 focus:border-sena-500 transition-colors">
                    <option value="">Todos los grupos</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ request('grupo_id') == $group->id ? 'selected' : '' }}>
                            {{ $group->numero_ficha }} - {{ $group->program->nombre ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            @if(request()->hasAny(['search', 'grupo_id']))
                <div class="md:col-span-3 flex justify-end">
                    <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Limpiar Filtros
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
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Nombre</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Documento</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Grupo</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Programa</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $student->id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $student->nombre }}</div>
                                    @if($student->hasAbsenceWarning())
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800" title="Alerta: 2+ Inasistencias">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Alerta
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $student->documento }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $student->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $student->group->numero_ficha ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $student->group->program->nombre ?? 'N/A' }}">
                                    {{ $student->group->program->nombre ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('students.show', $student) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" 
                                       title="Ver detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if(!auth()->user()->isCoordinator())
                                    <a href="{{ route('students.edit', $student) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors" 
                                       title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    @php
                                        $user = auth()->user();
                                        $canView = $user->canViewDisciplinaryActions();
                                        $hasAccess = false;
                                        if ($canView) {
                                            if ($user->isAdmin() || $user->isCoordinator()) {
                                                $hasAccess = true;
                                            } elseif ($user->isInstructor()) {
                                                $groupIds = $user->getAccessibleGroupIds();
                                                $hasAccess = in_array($student->group_id, $groupIds);
                                            }
                                        }
                                    @endphp
                                    @if($hasAccess)
                                    <a href="{{ route('students.disciplinary_actions.index', $student) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" 
                                       title="Llamados de Atención">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    @if(!auth()->user()->isCoordinator())
                                    <form action="{{ route('students.destroy', $student) }}" 
                                          method="POST" 
                                          class="inline"
                                          id="delete-student-{{ $student->id }}"
                                                  data-confirm-message="¿Estás seguro de eliminar este estudiante?">
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm font-medium">No hay estudiantes registrados</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    @if(method_exists($students, 'links'))
        <div class="flex items-center justify-between bg-white px-4 py-3 rounded-xl shadow-sm border border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                @if($students->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                        Anterior
                    </span>
                @else
                    <a href="{{ $students->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Anterior
                    </a>
                @endif
                @if($students->hasMorePages())
                    <a href="{{ $students->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Siguiente
                    </a>
                @else
                    <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                        Siguiente
                    </span>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $students->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $students->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $students->total() }}</span>
                        resultados
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        @if($students->onFirstPage())
                            <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-not-allowed">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $students->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        @endif
                        
                        @foreach($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                            @if($page == $students->currentPage())
                                <span class="relative inline-flex items-center px-4 py-2 border border-sena-500 bg-sena-50 text-sm font-medium text-sena-600">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                        
                        @if($students->hasMorePages())
                            <a href="{{ $students->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        @else
                            <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-not-allowed">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
