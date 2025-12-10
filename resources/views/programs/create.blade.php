<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear Programa') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Botón Volver -->
            <div class="flex justify-start">
                <a href="{{ route('programs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Programas
                </a>
            </div>

            <!-- Formulario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-gradient-to-r from-sena-50 to-sena-100 border-b border-sena-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-sena-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Información del Programa
                    </h3>
                </div>

                <form action="{{ route('programs.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- Nombre del Programa -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Programa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre"
                               value="{{ old('nombre') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('nombre') border-red-500 @enderror" 
                               placeholder="Ej: Tecnología en Análisis y Desarrollo de Software"
                               oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')"
                               required>
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Código -->
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                            Código del Programa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="codigo" 
                               id="codigo"
                               value="{{ old('codigo') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('codigo') border-red-500 @enderror" 
                               placeholder="Ej: 228106"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               required>
                        @error('codigo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Código único del programa según normativa SENA</p>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción <span class="text-gray-500 text-xs">(Opcional)</span>
                        </label>
                        <textarea name="descripcion" 
                                  id="descripcion"
                                  rows="4"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('descripcion') border-red-500 @enderror" 
                                  placeholder="Descripción del programa de formación...">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duración y Nivel -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="duracion_meses" class="block text-sm font-medium text-gray-700 mb-2">
                                Duración (Meses) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="duracion_meses" 
                                   id="duracion_meses"
                                   value="{{ old('duracion_meses') }}"
                                   min="1"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('duracion_meses') border-red-500 @enderror" 
                                   required>
                            @error('duracion_meses')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nivel" class="block text-sm font-medium text-gray-700 mb-2">
                                Nivel de Formación <span class="text-red-500">*</span>
                            </label>
                            <select name="nivel" 
                                    id="nivel"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('nivel') border-red-500 @enderror" 
                                    required>
                                <option value="">Seleccione un nivel</option>
                                <option value="Técnico" {{ old('nivel') == 'Técnico' ? 'selected' : '' }}>Técnico</option>
                                <option value="Tecnología" {{ old('nivel') == 'Tecnología' ? 'selected' : '' }}>Tecnología</option>
                                <option value="Especialización" {{ old('nivel') == 'Especialización' ? 'selected' : '' }}>Especialización</option>
                                <option value="Curso Corto" {{ old('nivel') == 'Curso Corto' ? 'selected' : '' }}>Curso Corto</option>
                            </select>
                            @error('nivel')
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
                               {{ old('activo', true) ? 'checked' : '' }}>
                        <label for="activo" class="ml-2 block text-sm text-gray-900">
                            Programa activo
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('programs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-sena-600 to-sena-700 hover:from-sena-700 hover:to-sena-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sena-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Programa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
