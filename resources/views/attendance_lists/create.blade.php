<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Registrar Inasistencias') }}
            </h2>
            <a href="{{ route('attendance-lists.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Selección de Grupo -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-gradient-to-r from-sena-50 to-sena-100 border-b border-sena-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 text-sena-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Seleccionar Grupo (Ficha)
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('attendance-lists.create') }}">
                        <div class="max-w-md">
                            <label for="group_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Grupo <span class="text-red-500">*</span>
                            </label>
                            <select name="group_id" 
                                    id="group_id" 
                                    onchange="this.form.submit()"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500">
                                <option value="">Seleccione un grupo...</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->numero_ficha }} - {{ $group->program->nombre ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($students) && $students->count() > 0)
                <!-- Información Importante -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-blue-700">
                                <strong>Importante:</strong> Este formulario registra únicamente <strong>INASISTENCIAS</strong>. 
                                Solo marque los estudiantes que <strong>NO asistieron</strong>. Los estudiantes que asisten no necesitan ser registrados.
                            </p>
                            <p class="text-sm text-blue-600 mt-2">
                                <strong>Norma SENA:</strong> Se enviará una <strong>alerta temprana</strong> cuando un estudiante tenga 2 días consecutivos o 4 inasistencias totales (antes de alcanzar los límites de 3 consecutivas o 5 totales). Se notificará a todos los instructores del programa para intervención preventiva.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('attendance-lists.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="group_id" value="{{ request('group_id') }}">
                    
                    @php
                        $selectedGroup = $groups->firstWhere('id', request('group_id'));
                        $instructorLider = $selectedGroup ? $selectedGroup->instructorLider : null;
                    @endphp

                    @php
                        $instructorCompetencia = null;
                        if (request('group_id') && request('competencia_id')) {
                            $instructorCompetencia = \App\Models\CompetenciaGroupInstructor::where('group_id', request('group_id'))
                                ->where('competencia_id', request('competencia_id'))
                                ->with('instructor')
                                ->first()
                                ?->instructor;
                        }
                    @endphp

                    @if($selectedGroup)
                        <!-- Información de Instructores -->
                        <div class="bg-sena-50 border-l-4 border-sena-500 p-4 rounded-lg">
                            <div class="space-y-3">
                                @if($instructorLider)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-sena-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-sena-900">
                                                Instructor Líder de la Ficha
                                            </p>
                                            <p class="text-sm text-sena-700">
                                                {{ $instructorLider->name }} ({{ $instructorLider->email }})
                                            </p>
                                            <p class="text-xs text-sena-600 mt-1">Recibe todas las alertas críticas de la ficha</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                ⚠️ No se ha asignado un Instructor Líder a esta ficha.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                                @if(request('competencia_id'))
                                    @if($instructorCompetencia)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-blue-900">
                                                    Instructor de esta Competencia
                                                </p>
                                                <p class="text-sm text-blue-700">
                                                    {{ $instructorCompetencia->name }} ({{ $instructorCompetencia->email }})
                                                </p>
                                                <p class="text-xs text-blue-600 mt-1">Recibe alertas específicas de esta competencia</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-yellow-700">
                                                    ⚠️ Esta competencia no tiene instructor asignado específicamente.
                                                </p>
                                                <p class="text-xs text-yellow-600 mt-1">Solo el instructor líder recibirá las alertas</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Formulario de Datos -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="px-6 py-4 bg-gradient-to-r from-sena-600 to-sena-700 border-b border-sena-800">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Datos de la Inasistencia
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" 
                                           name="fecha" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500" 
                                           value="{{ date('Y-m-d') }}" 
                                           required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Instructor <span class="text-red-500">*</span>
                                    </label>
                                    <select name="instructor_id" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500" 
                                            required>
                                        <option value="">Seleccione un instructor...</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{ $instructor->id }}">{{ $instructor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Competencia <span class="text-red-500">*</span>
                                    </label>
                                    <select name="competencia_id" 
                                            id="competencia_id" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500" 
                                            required 
                                            onchange="loadLearningOutcomes(this.value)">
                                        <option value="">Seleccione una competencia...</option>
                                        @foreach($competencias as $competencia)
                                            <option value="{{ $competencia->id }}" {{ request('competencia_id') == $competencia->id ? 'selected' : '' }}>
                                                {{ $competencia->codigo }} - {{ $competencia->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Obligatorio: Seleccione la competencia que se dictó este día</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Resultado de Aprendizaje <span class="text-gray-500 text-xs">(Opcional)</span>
                                    </label>
                                    <select name="learning_outcome_id" 
                                            id="learning_outcome_id" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500" 
                                            disabled>
                                        <option value="">Primero seleccione una competencia</option>
                                        @if(isset($learningOutcomes) && $learningOutcomes->count() > 0)
                                            @foreach($learningOutcomes as $lo)
                                                <option value="{{ $lo->id }}">{{ $lo->codigo }} - {{ $lo->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Opcional: Para tracking específico de RA</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Estudiantes -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="px-6 py-4 bg-gradient-to-r from-sena-600 to-sena-700 border-b border-sena-800">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Lista de Estudiantes - Marcar Inasistencias
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Marcar Inasistencia
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Estudiante
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Documento
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Observaciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $student)
                                        @php
                                            $competenciaId = request('competencia_id');
                                            $totalAbsences = 0;
                                            $hasWarning = false;
                                            
                                            if ($competenciaId) {
                                                $totalAbsences = $student->attendance_lists()
                                                    ->where('competencia_id', $competenciaId)
                                                    ->where('estado', 'ausente')
                                                    ->count();
                                                $hasWarning = $totalAbsences >= 2;
                                            }
                                        @endphp
                                        <tr class="{{ $hasWarning ? 'bg-yellow-50' : 'hover:bg-gray-50' }} transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" 
                                                           name="inasistencias[{{ $student->id }}]" 
                                                           value="1"
                                                           class="rounded border-gray-300 text-sena-600 shadow-sm focus:border-sena-500 focus:ring-sena-500 h-5 w-5">
                                                    <span class="ml-2 text-sm text-gray-700">Falta</span>
                                                </label>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $student->nombre }}
                                                    </div>
                                                    @if($hasWarning)
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800" title="Alerta: {{ $totalAbsences }} inasistencias">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ $totalAbsences }} faltas
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $student->documento }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="text" 
                                                       name="inasistencias[{{ $student->id }}][observaciones]" 
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 text-sm"
                                                       placeholder="Opcional">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Botón de Envío -->
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-sena-600 to-sena-700 hover:from-sena-700 hover:to-sena-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sena-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Registrar Inasistencias
                        </button>
                    </div>
                </form>

                <!-- Script para cargar Resultados de Aprendizaje -->
                <script>
                    function loadLearningOutcomes(competenciaId) {
                        const learningOutcomeSelect = document.getElementById('learning_outcome_id');
                        
                        if (!competenciaId) {
                            learningOutcomeSelect.innerHTML = '<option value="">Primero seleccione una competencia</option>';
                            learningOutcomeSelect.disabled = true;
                            return;
                        }

                        learningOutcomeSelect.disabled = true;
                        learningOutcomeSelect.innerHTML = '<option value="">Cargando...</option>';

                        fetch(`{{ route('attendance-lists.learning-outcomes') }}?competencia_id=${competenciaId}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                learningOutcomeSelect.innerHTML = '<option value="">Sin resultado de aprendizaje específico</option>';
                                
                                if (data.length > 0) {
                                    data.forEach(lo => {
                                        const option = document.createElement('option');
                                        option.value = lo.id;
                                        option.textContent = `${lo.codigo} - ${lo.nombre}`;
                                        learningOutcomeSelect.appendChild(option);
                                    });
                                }
                                
                                learningOutcomeSelect.disabled = false;
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                learningOutcomeSelect.innerHTML = '<option value="">Error al cargar</option>';
                            });
                    }

                    // Cargar resultados de aprendizaje si ya hay una competencia seleccionada
                    @if(request('competencia_id'))
                        document.addEventListener('DOMContentLoaded', function() {
                            loadLearningOutcomes('{{ request('competencia_id') }}');
                        });
                    @endif
                </script>
            @elseif(request('group_id'))
                <!-- Mensaje cuando no hay estudiantes -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>No hay estudiantes registrados</strong> en este grupo. Debe registrar estudiantes primero.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
