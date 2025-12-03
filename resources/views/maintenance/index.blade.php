<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modo Mantenimiento - SENA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-sena-50 via-blue-50 to-indigo-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-sena-600 to-sena-700 px-8 py-12 text-center">
                <div class="inline-block bg-white/20 rounded-full p-4 mb-4 animate-pulse-slow">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">Modo Mantenimiento</h1>
                <p class="text-sena-100 text-lg">Sistema de Gestión Académica SENA</p>
            </div>
            
            <!-- Content -->
            <div class="px-8 py-12">
                <div class="text-center mb-8">
                    <p class="text-gray-700 text-lg mb-4">
                        {{ $message }}
                    </p>
                    
                    @if(isset($estimated_time) && $estimated_time)
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-blue-700 font-medium">
                                <strong>Tiempo estimado:</strong> {{ $estimated_time }}
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-gray-800 font-semibold mb-3">¿Qué está pasando?</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Estamos realizando mejoras y actualizaciones en el sistema para brindarte una mejor experiencia. 
                            Durante este tiempo, el sistema no estará disponible para usuarios regulares.
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-sena-600 hover:bg-sena-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sena-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Intentar Acceder (Admin)
                        </a>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <p class="text-center text-gray-500 text-sm">
                        Si eres administrador y necesitas acceso, inicia sesión con tus credenciales.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Logo SENA -->
        <div class="text-center mt-8">
            <div class="inline-flex items-center space-x-2 text-gray-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium">Servicio Nacional de Aprendizaje - SENA</span>
            </div>
        </div>
    </div>
    
    <!-- Auto-refresh cada 30 segundos para verificar si el mantenimiento terminó -->
    <script>
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>

