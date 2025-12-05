<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reporte de Inasistencias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <form action="{{ route('reports.absences') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Grupo</label>
                        <select name="group_id" class="form-select w-full rounded-md shadow-sm border-gray-300">
                            <option value="">Todos los Grupos</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->nombre }} ({{ $group->numero_ficha }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Competencia</label>
                        <select name="competencia_id" class="form-select w-full rounded-md shadow-sm border-gray-300">
                            <option value="">Todas las Competencias</option>
                            @foreach($competencias as $competencia)
                                <option value="{{ $competencia->id }}" {{ request('competencia_id') == $competencia->id ? 'selected' : '' }}>
                                    {{ Str::limit($competencia->nombre, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Documento Estudiante</label>
                        <input type="text" name="student_document" value="{{ request('student_document') }}" class="form-input w-full rounded-md shadow-sm border-gray-300" placeholder="Buscar por documento...">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resultados -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Resultados ({{ $attendances->total() }})</h3>
                    <a href="{{ route('reports.absences.export', request()->all()) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ficha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Competencia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observación</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->fecha->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $attendance->student->nombre }}</div>
                                        <div class="text-sm text-gray-500">{{ $attendance->student->documento }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->student->group->numero_ficha }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($attendance->competencia->nombre, 20) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $attendance->estado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->observaciones ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No se encontraron registros con los filtros seleccionados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $attendances->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
