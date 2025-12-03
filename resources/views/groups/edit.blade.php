<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Ficha') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Botón Volver -->
            <div class="flex justify-start">
                <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Fichas
                </a>
            </div>

            <!-- Formulario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-gradient-to-r from-sena-50 to-sena-100 border-b border-sena-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-sena-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Información de la Ficha: {{ $group->numero_ficha }}
                    </h3>
                </div>

                <form action="{{ route('groups.update', $group) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Número de Ficha -->
                    <div>
                        <label for="numero_ficha" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Ficha <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="numero_ficha" 
                               id="numero_ficha"
                               value="{{ old('numero_ficha', $group->numero_ficha) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('numero_ficha') border-red-500 @enderror" 
                               required>
                        @error('numero_ficha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Programa de Formación -->
                    <div>
                        <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Programa de Formación <span class="text-red-500">*</span>
                        </label>
                        <select name="program_id" 
                                id="program_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('program_id') border-red-500 @enderror" 
                                required>
                            <option value="">Seleccione un programa</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" 
                                        {{ old('program_id', $group->program_id) == $program->id ? 'selected' : '' }}>
                                    {{ $program->codigo }} - {{ $program->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('program_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructor Líder -->
                    <div>
                        <label for="instructor_lider_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Instructor Líder <span class="text-gray-500 text-xs">(Opcional)</span>
                        </label>
                        <select name="instructor_lider_id" 
                                id="instructor_lider_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500">
                            <option value="">Sin instructor líder asignado</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('instructor_lider_id', $group->instructor_lider_id) == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->name }} ({{ $instructor->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jornada -->
                    <div>
                        <label for="jornada" class="block text-sm font-medium text-gray-700 mb-2">
                            Jornada <span class="text-red-500">*</span>
                        </label>
                        <select name="jornada" 
                                id="jornada"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('jornada') border-red-500 @enderror" 
                                required>
                            <option value="mañana" {{ old('jornada', $group->jornada) == 'mañana' ? 'selected' : '' }}>Mañana</option>
                            <option value="tarde" {{ old('jornada', $group->jornada) == 'tarde' ? 'selected' : '' }}>Tarde</option>
                            <option value="noche" {{ old('jornada', $group->jornada) == 'noche' ? 'selected' : '' }}>Noche</option>
                            <option value="mixta" {{ old('jornada', $group->jornada) == 'mixta' ? 'selected' : '' }}>Mixta</option>
                        </select>
                        @error('jornada')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fechas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Inicio <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="fecha_inicio" 
                                   id="fecha_inicio"
                                   value="{{ old('fecha_inicio', $group->fecha_inicio ? $group->fecha_inicio->format('Y-m-d') : '') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('fecha_inicio') border-red-500 @enderror" 
                                   required>
                            @error('fecha_inicio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Fin <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="fecha_fin" 
                                   id="fecha_fin"
                                   value="{{ old('fecha_fin', $group->fecha_fin ? $group->fecha_fin->format('Y-m-d') : '') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('fecha_fin') border-red-500 @enderror" 
                                   required>
                            @error('fecha_fin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Estado Activo -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="activo" 
                               id="activo"
                               value="1" 
                               class="h-4 w-4 text-sena-600 focus:ring-sena-500 border-gray-300 rounded"
                               {{ old('activo', $group->activo) ? 'checked' : '' }}>
                        <label for="activo" class="ml-2 block text-sm text-gray-900">
                            Ficha activa
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-sena-600 to-sena-700 hover:from-sena-700 hover:to-sena-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sena-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Actualizar Ficha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
