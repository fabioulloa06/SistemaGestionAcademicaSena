@extends('layouts.app')

@section('title', 'Registrar Aprendiz')
@section('page-title', 'Registrar Nuevo Aprendiz')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Card del formulario -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 text-white" style="background: linear-gradient(135deg, #238276 0%, #1a6b60 100%);">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Registrar Nuevo Aprendiz</h2>
                    <p class="text-white/90 text-sm mt-1">Completa el formulario para registrar un nuevo aprendiz</p>
                </div>
                <a href="{{ route('aprendices.index') }}" class="text-white/90 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('aprendices.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Nombres -->
            <div>
                <label for="nombres" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombres <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nombres" 
                    id="nombres" 
                    required 
                    value="{{ old('nombres') }}"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 transition-colors"
                    onfocus="this.style.borderColor='#fc7323'; this.style.boxShadow='0 0 0 3px rgba(252, 115, 35, 0.1)';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';"
                    placeholder="Ej: Juan Carlos"
                >
                @error('nombres')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Apellidos -->
            <div>
                <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">
                    Apellidos <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="apellidos" 
                    id="apellidos" 
                    required 
                    value="{{ old('apellidos') }}"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 transition-colors"
                    onfocus="this.style.borderColor='#fc7323'; this.style.boxShadow='0 0 0 3px rgba(252, 115, 35, 0.1)';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';"
                    placeholder="Ej: Pérez García"
                >
                @error('apellidos')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Tipo de Documento -->
            <div>
                <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Documento <span class="text-red-500">*</span>
                </label>
                <select 
                    name="tipo_documento" 
                    id="tipo_documento" 
                    required
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 transition-colors"
                    onfocus="this.style.borderColor='#fc7323'; this.style.boxShadow='0 0 0 3px rgba(252, 115, 35, 0.1)';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';"
                >
                    <option value="">Seleccione...</option>
                    <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía (CC)</option>
                    <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería (CE)</option>
                    <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad (TI)</option>
                    <option value="PAS" {{ old('tipo_documento') == 'PAS' ? 'selected' : '' }}>Pasaporte (PAS)</option>
                </select>
                @error('tipo_documento')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Número de Documento -->
            <div>
                <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Documento <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="numero_documento" 
                    id="numero_documento" 
                    required 
                    value="{{ old('numero_documento') }}"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 transition-colors"
                    onfocus="this.style.borderColor='#fc7323'; this.style.boxShadow='0 0 0 3px rgba(252, 115, 35, 0.1)';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';"
                    placeholder="Ej: 1234567890"
                >
                @error('numero_documento')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Correo Electrónico <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    required 
                    value="{{ old('email') }}"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 transition-colors"
                    onfocus="this.style.borderColor='#fc7323'; this.style.boxShadow='0 0 0 3px rgba(252, 115, 35, 0.1)';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';"
                    placeholder="aprendiz@ejemplo.com"
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Teléfono -->
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono
                </label>
                <input 
                    type="text" 
                    name="telefono" 
                    id="telefono"
                    value="{{ old('telefono') }}"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 transition-colors"
                    onfocus="this.style.borderColor='#fc7323'; this.style.boxShadow='0 0 0 3px rgba(252, 115, 35, 0.1)';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';"
                    placeholder="Ej: 3001234567"
                >
                @error('telefono')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Contraseña <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 transition-colors"
                    onfocus="this.style.borderColor='#fc7323'; this.style.boxShadow='0 0 0 3px rgba(252, 115, 35, 0.1)';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';"
                    placeholder="Mínimo 8 caracteres"
                >
                @error('password')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmar Contraseña <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    required 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 transition-colors"
                    onfocus="this.style.borderColor='#fc7323'; this.style.boxShadow='0 0 0 3px rgba(252, 115, 35, 0.1)';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';"
                    placeholder="Repite la contraseña"
                >
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <a href="{{ route('aprendices.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all transform hover:scale-105"
                    style="background-color: #fc7323;"
                    onmouseover="this.style.backgroundColor='#e8651f'"
                    onmouseout="this.style.backgroundColor='#fc7323'"
                >
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Registrar Aprendiz
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
