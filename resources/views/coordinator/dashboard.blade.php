@extends('layouts.app')

@section('title', 'Dashboard - Coordinador')
@section('page-title', 'Panel de Vigilancia y Revisión')

@section('content')
<!-- Header con saludo -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        Bienvenido, {{ Auth::user()->name }}! 👋
    </h1>
    <p class="text-gray-600">
        Panel de Vigilancia y Revisión - Coordinador Académico
    </p>
</div>

<!-- Cards de Estadísticas Principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Card 1: Total Estudiantes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Estudiantes</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalStudents ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">Activos en el sistema</p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 2: Faltas Académicas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Faltas Académicas</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($faltasAcademicas ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">Este mes</p>
            </div>
            <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 3: Faltas Disciplinarias -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Faltas Disciplinarias</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($faltasDisciplinarias ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">Este mes</p>
            </div>
            <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 4: Total Llamados -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Llamados</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format(($llamadosVerbal ?? 0) + ($llamadosEscrito ?? 0)) }}</p>
                <p class="text-xs text-gray-500 mt-1">Verbal: {{ $llamadosVerbal ?? 0 }} | Escrito: {{ $llamadosEscrito ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Acciones Rápidas -->
<div class="rounded-xl shadow-lg p-6 mb-6 text-white" style="background: linear-gradient(135deg, #4d8e37 0%, #3d7230 100%);">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold mb-2">Acciones de Revisión</h3>
            <p class="text-white/90 text-sm">Revisa y vigila los llamados de atención del sistema</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('disciplinary-actions.global-index') }}" class="px-6 py-3 bg-white rounded-lg font-semibold transition-colors shadow-md" style="color: #4d8e37;" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='white'">
                📋 Ver Todos los Llamados
            </a>
            <a href="{{ route('reports.index') }}" class="px-6 py-3 bg-white/10 backdrop-blur-sm text-white rounded-lg font-semibold hover:bg-white/20 transition-colors border border-white/20">
                📊 Ver Reportes
            </a>
        </div>
    </div>
</div>

<!-- Gráficos y Tablas -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Llamados por Programa -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Llamados por Programa (Top 5)</h3>
        <div class="space-y-3">
            @forelse($llamadosPorPrograma ?? [] as $item)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $item->nombre }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-lg font-bold text-red-600">{{ $item->total }}</span>
                    <span class="text-xs text-gray-500">llamados</span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
            @endforelse
        </div>
    </div>

    <!-- Llamados por Ficha -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Llamados por Ficha (Top 5)</h3>
        <div class="space-y-3">
            @forelse($llamadosPorFicha ?? [] as $item)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $item->numero_ficha }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-lg font-bold text-red-600">{{ $item->total }}</span>
                    <span class="text-xs text-gray-500">llamados</span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Estudiantes con más Llamados -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Estudiantes con Más Llamados (Top 5)</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Llamados</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($estudiantesConMasLlamados ?? [] as $estudiante)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $estudiante->nombre }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $estudiante->documento }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            {{ $estudiante->total }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('disciplinary-actions.global-index') }}?student={{ $estudiante->documento }}" class="text-blue-600 hover:text-blue-900">Ver Detalle</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay datos disponibles</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Llamados Recientes -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Llamados de Atención Recientes</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ficha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Llamado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($llamadosRecientes ?? [] as $llamado)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $llamado->date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $llamado->student->nombre ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $llamado->student->group->numero_ficha ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $llamado->tipo_falta == 'Académica' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $llamado->tipo_falta }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $llamado->tipo_llamado == 'verbal' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $llamado->tipo_llamado == 'verbal' ? 'Verbal' : 'Escrito' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('disciplinary-actions.print', $llamado) }}" class="text-blue-600 hover:text-blue-900" target="_blank">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay llamados recientes</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

