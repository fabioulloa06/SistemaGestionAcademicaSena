@extends('layouts.app')

@section('title', 'Llamados de Atención - Global')
@section('page-title', 'Llamados de Atención')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Historial Disciplinario Global</h1>
            <p class="text-sm text-gray-600 mt-1">Consulta todos los llamados de atención del sistema</p>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-sena-600 to-sena-700">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Estudiante</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Ficha</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Tipo Falta</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Tipo Llamado</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Gravedad</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Descripción</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($actions as $action)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $action->date->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('students.show', $action->student) }}" class="text-sm font-medium text-sena-600 hover:text-sena-700">
                                    {{ $action->student->nombre }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $action->student->group->numero_ficha ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $action->tipo_falta == 'Académica' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $action->tipo_falta }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $action->tipo_llamado == 'verbal' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $action->tipo_llamado == 'verbal' ? 'Verbal' : 'Escrito' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $gravedadColors = [
                                        'Leve' => 'bg-gray-100 text-gray-800',
                                        'Grave' => 'bg-yellow-100 text-yellow-800',
                                        'Gravísima' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $gravedadColors[$action->gravedad ?? 'Leve'] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                    {{ $action->gravedad }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $action->description }}">
                                    {{ Str::limit($action->description, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('disciplinary-actions.print', $action) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors" 
                                       title="Imprimir / PDF" 
                                       target="_blank">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </a>
                                    @if(!auth()->user()->isCoordinator())
                                    <form action="{{ route('disciplinary-actions.destroy', $action) }}" 
                                          method="POST" 
                                          class="inline"
                                          id="delete-action-{{ $action->id }}"
                                          data-confirm-message="¿Estás seguro de eliminar este registro?">
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
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm font-medium">No hay llamados de atención registrados.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    @if(method_exists($actions, 'links'))
        <div class="flex justify-center">
            {{ $actions->links() }}
        </div>
    @endif
</div>
@endsection
