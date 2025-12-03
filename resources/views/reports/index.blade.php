<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes y Estadísticas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Header con información del período -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-gradient-to-r from-sena-50 to-sena-100 border-b border-sena-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-sena-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Estadísticas del Mes Actual
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Período: {{ \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y') }} - {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                            </p>
                        </div>
                        <a href="{{ route('reports.absences') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Ver Reporte Detallado
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Inasistencias -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-red-700 border-b border-red-800">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Estadísticas de Inasistencias
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Métricas principales -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <dt class="text-sm font-medium text-gray-500">Total Inasistencias</dt>
                                    <dd class="text-3xl font-bold text-red-600">{{ $totalInasistencias }}</dd>
                                </div>
                            </div>
                        </div>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <dt class="text-sm font-medium text-gray-500">Consecutivas (≥3 días)</dt>
                                    <dd class="text-3xl font-bold text-orange-600">{{ $consecutiveAbsences }}</dd>
                                </div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <dt class="text-sm font-medium text-gray-500">Por Competencia (≥4)</dt>
                                    <dd class="text-3xl font-bold text-yellow-600">{{ $absencesByCompetence }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top 5 Grupos con más inasistencias -->
                    @if($absencesByGroup->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Top 5 Grupos con más Inasistencias</h4>
                        <div class="space-y-2">
                            @foreach($absencesByGroup as $group)
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">Ficha {{ $group->numero_ficha }}</span>
                                </div>
                                <span class="text-sm font-bold text-red-600">{{ $group->total }} inasistencias</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Top 5 Competencias con más inasistencias -->
                    @if($absencesByCompetencia->count() > 0)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Top 5 Competencias con más Inasistencias</h4>
                        <div class="space-y-2">
                            @foreach($absencesByCompetencia as $comp)
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center flex-1 min-w-0">
                                    <svg class="w-5 h-5 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900 truncate">{{ $comp->nombre }}</span>
                                </div>
                                <span class="text-sm font-bold text-red-600 ml-2">{{ $comp->total }} inasistencias</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Estadísticas de Llamados de Atención -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 bg-gradient-to-r from-yellow-600 to-yellow-700 border-b border-yellow-800">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Estadísticas de Llamados de Atención
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Métricas principales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <dt class="text-sm font-medium text-gray-500">Total Llamados</dt>
                                    <dd class="text-3xl font-bold text-yellow-600">{{ $totalLlamados }}</dd>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <dt class="text-sm font-medium text-gray-500">Verbales</dt>
                                    <dd class="text-3xl font-bold text-blue-600">{{ $llamadosVerbales }}</dd>
                                </div>
                            </div>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <dt class="text-sm font-medium text-gray-500">Escritos</dt>
                                    <dd class="text-3xl font-bold text-red-600">{{ $llamadosEscritos }}</dd>
                                </div>
                            </div>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <dt class="text-sm font-medium text-gray-500">Disciplinarias</dt>
                                    <dd class="text-3xl font-bold text-purple-600">{{ $faltasDisciplinarias }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desglose por tipo y gravedad -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Por tipo de falta -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Por Tipo de Falta</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between bg-indigo-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-indigo-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">Académicas</span>
                                    </div>
                                    <span class="text-sm font-bold text-indigo-600">{{ $faltasAcademicas }}</span>
                                </div>
                                <div class="flex items-center justify-between bg-purple-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-purple-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">Disciplinarias</span>
                                    </div>
                                    <span class="text-sm font-bold text-purple-600">{{ $faltasDisciplinarias }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Por gravedad -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Por Gravedad</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-2">Leve</span>
                                        <span class="text-sm font-medium text-gray-900">Leve</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-600">{{ $gravedadLeve }}</span>
                                </div>
                                <div class="flex items-center justify-between bg-yellow-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">Grave</span>
                                        <span class="text-sm font-medium text-gray-900">Grave</span>
                                    </div>
                                    <span class="text-sm font-bold text-yellow-600">{{ $gravedadGrave }}</span>
                                </div>
                                <div class="flex items-center justify-between bg-red-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">Gravísima</span>
                                        <span class="text-sm font-medium text-gray-900">Gravísima</span>
                                    </div>
                                    <span class="text-sm font-bold text-red-600">{{ $gravedadGravísima }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top estudiantes con más llamados -->
                    @if($topEstudiantesLlamados->count() > 0)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Top 5 Estudiantes con más Llamados</h4>
                        <div class="space-y-2">
                            @foreach($topEstudiantesLlamados as $item)
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center flex-1 min-w-0">
                                    <svg class="w-5 h-5 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900 truncate">{{ $item->student->nombre ?? 'N/A' }}</span>
                                    <span class="text-xs text-gray-500 ml-2">({{ $item->student->documento ?? 'N/A' }})</span>
                                </div>
                                <span class="text-sm font-bold text-yellow-600 ml-2">{{ $item->total }} llamados</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Enlaces a reportes detallados -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('reports.absences') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 hover:shadow-2xl transition duration-300 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-800">Reporte Detallado de Inasistencias</h3>
                                <p class="text-gray-600 mt-1 text-sm">Consulta el detalle completo de inasistencias con filtros avanzados.</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('disciplinary-actions.global-index') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 hover:shadow-2xl transition duration-300 border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 group-hover:bg-yellow-500 group-hover:text-white transition duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-800">Historial Disciplinario Global</h3>
                                <p class="text-gray-600 mt-1 text-sm">Consulta todos los llamados de atención del sistema.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
