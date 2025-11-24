<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes y Estadísticas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Reporte de Inasistencias -->
                <a href="{{ route('reports.absences') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 hover:shadow-2xl transition duration-300 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition duration-300">
                                <i class="bi bi-calendar-x text-3xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-800">Reporte de Inasistencias</h3>
                                <p class="text-gray-600 mt-1 text-sm">Detalle de fallas por grupo, competencia y estudiante.</p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Próximos Reportes (Placeholder) -->
                <div class="bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-gray-300 opacity-75">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-gray-200 text-gray-400">
                            <i class="bi bi-bar-chart-line text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-500">Rendimiento Académico</h3>
                            <p class="text-gray-500 mt-1 text-sm">Próximamente: Análisis de calificaciones por competencia.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-gray-300 opacity-75">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-gray-200 text-gray-400">
                            <i class="bi bi-exclamation-triangle text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-500">Estadísticas Disciplinarias</h3>
                            <p class="text-gray-500 mt-1 text-sm">Próximamente: Resumen de faltas y sanciones.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
