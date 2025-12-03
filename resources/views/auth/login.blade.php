@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-md w-full">
        <!-- Card de Login -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header con colores SENA -->
            <div class="px-8 py-6 text-white" style="background: linear-gradient(135deg, #4d8e37 0%, #3d7230 100%);">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-lg">
                        <span class="font-bold text-3xl" style="color: #4d8e37;">S</span>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-center">Sistema de Gestión Académica</h2>
                <p class="text-center text-white/90 text-sm mt-1">Servicio Nacional de Aprendizaje - SENA</p>
            </div>

            <!-- Formulario -->
            <div class="px-8 py-8">
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                name="email" 
                                type="text" 
                                autocomplete="email" 
                                required 
                                value="{{ old('email') }}"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 transition-colors"
                                style="--tw-ring-color: #fc7323;"
                                onfocus="this.style.borderColor='#fc7323'; this.style.boxShadow='0 0 0 3px rgba(252, 115, 35, 0.1)';"
                                onblur="this.style.borderColor=''; this.style.boxShadow='';"
                                placeholder="admin@admin.com"
                            >
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                autocomplete="current-password" 
                                required 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 transition-colors"
                                style="--tw-ring-color: #fc7323;"
                                onfocus="this.style.borderColor='#fc7323'; this.style.boxShadow='0 0 0 3px rgba(252, 115, 35, 0.1)';"
                                onblur="this.style.borderColor=''; this.style.boxShadow='';"
                                placeholder="••••••••"
                            >
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                id="remember" 
                                name="remember" 
                                type="checkbox" 
                                class="h-4 w-4 border-gray-300 rounded"
                                style="accent-color: #fc7323;"
                            >
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Recordarme
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all transform hover:scale-[1.02]"
                        style="background-color: #fc7323;"
                        onmouseover="this.style.backgroundColor='#e8651f'"
                        onmouseout="this.style.backgroundColor='#fc7323'"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Iniciar Sesión
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <p class="text-center text-xs text-gray-500">
                    © {{ date('Y') }} Servicio Nacional de Aprendizaje - SENA. Todos los derechos reservados.
                </p>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                ¿Necesitas ayuda? 
                <a href="#" class="font-medium hover:underline" style="color: #4d8e37;">
                    Contacta al administrador
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
