<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear Coordinador Académico') }}
            </h2>
            <a href="{{ route('coordinators.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-gradient-to-r from-sena-50 to-sena-100 border-b border-sena-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-sena-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Información del Coordinador Académico
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">El coordinador académico es responsable de la gestión curricular, asignación de instructores y supervisión académica.</p>
                </div>
                <form action="{{ route('coordinators.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="space-y-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('name') border-red-500 @enderror"
                                   placeholder="Ej: Juan Carlos Pérez">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('email') border-red-500 @enderror"
                                   placeholder="coordinador@sena.edu.co">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono <span class="text-gray-500 text-xs">(Opcional)</span>
                            </label>
                            <input type="text" 
                                   name="telefono" 
                                   id="telefono" 
                                   value="{{ old('telefono') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('telefono') border-red-500 @enderror"
                                   placeholder="Ej: 3001234567">
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required
                                   minlength="8"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500 @error('password') border-red-500 @enderror"
                                   placeholder="Mínimo 8 caracteres">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">La contraseña debe tener al menos 8 caracteres.</p>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   required
                                   minlength="8"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-sena-500 focus:ring-sena-500"
                                   placeholder="Repite la contraseña">
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <a href="{{ route('coordinators.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sena-500">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-sena-600 hover:bg-sena-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sena-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crear Coordinador
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

