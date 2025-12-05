@extends('layouts.app')

@section('title', 'Dashboard - Instructor')
@section('page-title', 'Panel del Instructor')

@section('content')
<!-- Header con saludo -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        Bienvenido, {{ Auth::user()->name }}! 👋
    </h1>
    <p class="text-gray-600">
        Panel del Instructor - Gestión de Inasistencias y Llamados de Atención
    </p>
</div>

<!-- Cards de Estadísticas Principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Card 1: Total Estudiantes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Mis Estudiantes</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalStudents ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">En mis grupos asignados</p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 2: Inasistencias -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Inasistencias</p>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($totalInasistencias ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">Este mes</p>
            </div>
            <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 3: Fallas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Fallas</p>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($totalFallas ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">Este mes</p>
            </div>
            <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 4: Llamados Creados -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Llamados Creados</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">{{ number_format($llamadosCreados ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">Este mes</p>
            </div>
            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Acciones Rápidas -->
<div class="rounded-xl shadow-lg p-6 mb-6 text-white" style="background: linear-gradient(135deg, #4d8e37 0%, #3d7230 100%);">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold mb-2">Acciones Rápidas</h3>
            <p class="text-white/90 text-sm">Gestiona tus actividades diarias</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('attendance-lists.create') }}" class="px-6 py-3 bg-white rounded-lg font-semibold transition-colors shadow-md" style="color: #4d8e37;" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='white'">
                📝 Registrar Inasistencias
            </a>
            <a href="{{ route('disciplinary-actions.global-index') }}" class="px-6 py-3 bg-white/10 backdrop-blur-sm text-white rounded-lg font-semibold hover:bg-white/20 transition-colors border border-white/20">
                ⚠️ Ver Llamados
            </a>
        </div>
    </div>
</div>

<!-- Mis Grupos y Competencias -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Mis Grupos -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis Grupos Asignados</h3>
        <div class="space-y-3">
            @forelse($groups ?? [] as $group)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $group->numero_ficha }}</p>
                    <p class="text-xs text-gray-500">{{ $group->program->nombre ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <a href="{{ route('groups.show', $group) }}" class="text-blue-600 hover:text-blue-900 text-sm">Ver</a>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No tienes grupos asignados</p>
            @endforelse
        </div>
    </div>

    <!-- Mis Competencias -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis Competencias</h3>
        <div class="space-y-3">
            @forelse($competencias ?? [] as $competencia)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $competencia->nombre_competencia ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $competencia->codigo_competencia ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <a href="{{ route('competencias.show', $competencia) }}" class="text-blue-600 hover:text-blue-900 text-sm">Ver</a>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No tienes competencias asignadas</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Últimas Inasistencias -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Últimas Inasistencias Registradas</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Competencia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($ultimasAsistencias ?? [] as $asistencia)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asistencia->fecha->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $asistencia->student->nombre ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asistencia->competencia->nombre ?? 'General' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asistencia->estado === 'presente' ? 'bg-green-100 text-green-800' : ($asistencia->estado === 'ausente' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($asistencia->estado) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay inasistencias recientes</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Últimos Llamados -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Últimos Llamados de Atención</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Llamado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($ultimosLlamados ?? [] as $llamado)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $llamado->date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $llamado->student->nombre ?? 'N/A' }}</td>
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
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay llamados recientes</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

