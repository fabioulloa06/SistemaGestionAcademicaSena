@extends('layouts.app')

@section('title', 'Planes de Mejoramiento')
@section('page-title', 'Planes de Mejoramiento')

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Planes de Mejoramiento</h1>
            <p class="text-sm text-gray-600 mt-1">Gestiona los planes de mejoramiento de los estudiantes</p>
        </div>
        @if(!auth()->user()->isCoordinator())
        <a href="{{ route('improvement-plans.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-sena-600 to-sena-700 text-white font-semibold rounded-lg shadow-md hover:from-sena-700 hover:to-sena-800 transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Plan
        </a>
        @endif
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-sena-600 to-sena-700">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Estudiante</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Ficha</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Tipo Falta</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Gravedad</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Fecha Inicio</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Fecha Fin</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Responsable</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($plans as $plan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $plan->disciplinaryAction->student->nombre ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $plan->disciplinaryAction->student->group->numero_ficha ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $plan->disciplinaryAction->tipo_falta == 'Académica' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $plan->disciplinaryAction->tipo_falta ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $gravedadColors = [
                                        'Leve' => 'bg-gray-100 text-gray-800',
                                        'Grave' => 'bg-yellow-100 text-yellow-800',
                                        'Gravísima' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $gravedadColors[$plan->disciplinaryAction->gravedad ?? 'Leve'] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                    {{ $plan->disciplinaryAction->gravedad ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $plan->start_date->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $plan->end_date->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $estadoColors = [
                                        'Cumplido' => 'bg-green-100 text-green-800',
                                        'Incumplido' => 'bg-red-100 text-red-800',
                                        'En Progreso' => 'bg-yellow-100 text-yellow-800',
                                        'Pendiente' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $color = $estadoColors[$plan->status ?? 'Pendiente'] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                    {{ $plan->status ?? 'Pendiente' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $plan->instructor->nombre ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('improvement-plans.show', $plan) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" 
                                       title="Ver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if(!auth()->user()->isCoordinator())
                                    <a href="{{ route('improvement-plans.edit', $plan) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors" 
                                       title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    <a href="{{ route('improvement-plans.print', $plan) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors" 
                                       title="Imprimir / PDF" 
                                       target="_blank">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm font-medium">No hay planes de mejoramiento registrados.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    @if(method_exists($plans, 'links'))
        <div class="flex justify-center">
            {{ $plans->links() }}
        </div>
    @endif
</div>
@endsection
