<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Portal - Aprendiz') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Bienvenida -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Hola, {{ $student->nombre }}</h3>
                <p class="text-gray-600">Ficha: {{ $student->group->numero_ficha }} - {{ $student->group->program->nombre }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Tarjeta Asistencia -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Mi Asistencia</h4>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-3xl font-bold text-green-600">{{ $attendanceSummary['presente'] }}</span>
                            <span class="text-sm text-gray-500 block">Asistencias</span>
                        </div>
                        <div>
                            <span class="text-3xl font-bold text-red-600">{{ $attendanceSummary['ausente'] }}</span>
                            <span class="text-sm text-gray-500 block">Fallas</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            @php
                                $total = $attendanceSummary['total'] > 0 ? $attendanceSummary['total'] : 1;
                                $percentage = ($attendanceSummary['presente'] / $total) * 100;
                            @endphp
                            <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}% de Asistencia</p>
                    </div>
                </div>

                <!-- Tarjeta Disciplinaria -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Estado Disciplinario</h4>
                    @if($activeDisciplinary > 0)
                        <div class="text-center py-4">
                            <span class="text-4xl text-orange-500">⚠️</span>
                            <p class="text-red-600 font-bold mt-2">Tienes {{ $activeDisciplinary }} reportes</p>
                            <p class="text-sm text-gray-500">Revisa tu correo o acércate a coordinación.</p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <span class="text-4xl text-green-500">✅</span>
                            <p class="text-green-600 font-bold mt-2">Sin reportes activos</p>
                            <p class="text-sm text-gray-500">¡Sigue así!</p>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Llamados de Atención -->
            @if(($recentDisciplinaryActions ?? collect())->count() > 0)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Mis Llamados de Atención</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Llamado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentDisciplinaryActions as $llamado)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $llamado->date->format('d/m/Y') }}</td>
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
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($llamado->description ?? 'Sin descripción', 50) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif


            <!-- Últimas Asistencias -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Últimos Registros de Asistencia</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Competencia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observación</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentAttendances as $attendance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->fecha->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->competencia->nombre ?? 'General' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $attendance->estado === 'presente' ? 'bg-green-100 text-green-800' : 
                                               ($attendance->estado === 'ausente' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($attendance->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->observaciones ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay registros recientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
