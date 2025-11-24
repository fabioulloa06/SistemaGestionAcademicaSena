<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SENA Control Asistencias') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --sena-orange: #fc7323;
            --sena-green: #39a900;
            --sena-blue: #00324d;
        }
        .bg-sena-orange { background-color: var(--sena-orange); }
        .bg-sena-green { background-color: var(--sena-green); }
        .bg-sena-blue { background-color: var(--sena-blue); }
        .text-sena-orange { color: var(--sena-orange); }
        .text-sena-green { color: var(--sena-green); }
        .text-sena-blue { color: var(--sena-blue); }
        
        .btn-sena {
            background-color: var(--sena-orange);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-sena:hover {
            background-color: #e05d0b;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#fc7323] selection:text-white">
        
        <!-- Background Pattern -->
        <div class="absolute inset-0 z-0 opacity-5">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="#00324d"></path>
            </svg>
        </div>

        <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                
                <!-- Left Side: Branding -->
                <div class="text-center md:text-left space-y-6">
                    <div class="flex justify-center md:justify-start">
                        <div class="bg-white p-6 rounded-2xl shadow-2xl">
                            <img src="{{ asset('images/logoSena.png') }}" alt="SENA Logo" class="w-24 h-24 object-contain">
                        </div>
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl font-bold text-[#00324d] tracking-tight">
                        Sistema de Control <br>
                        <span class="text-[#fc7323]">de Asistencias</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Gestione eficientemente la asistencia de aprendices, instructores y programas de formación. 
                        Una herramienta diseñada para la excelencia académica del SENA.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start pt-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-8 py-3 text-lg font-semibold rounded-lg bg-[#00324d] text-white hover:bg-[#002233] transition shadow-lg">
                                    Ir al Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-8 py-3 text-lg font-semibold rounded-lg bg-[#fc7323] text-white hover:bg-[#e05d0b] transition shadow-lg transform hover:-translate-y-1">
                                    Iniciar Sesión
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-8 py-3 text-lg font-semibold rounded-lg border-2 border-[#00324d] text-[#00324d] hover:bg-[#00324d] hover:text-white transition shadow-md">
                                        Registrarse
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>

                <!-- Right Side: Illustration/Image -->
                <div class="hidden md:block relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#39a900] to-[#00324d] rounded-2xl transform rotate-3 opacity-20 blur-xl"></div>
                    <div class="relative bg-white p-8 rounded-2xl shadow-2xl border-t-4 border-[#fc7323]">
                        <div class="space-y-4">
                            <div class="h-32 bg-gray-100 rounded-lg animate-pulse"></div>
                            <div class="space-y-2">
                                <div class="h-4 bg-gray-100 rounded w-3/4"></div>
                                <div class="h-4 bg-gray-100 rounded w-1/2"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 pt-4">
                                <div class="h-20 bg-blue-50 rounded-lg border border-blue-100 flex items-center justify-center">
                                    <i class="text-[#00324d] text-2xl font-bold">SENA</i>
                                </div>
                                <div class="h-20 bg-orange-50 rounded-lg border border-orange-100 flex items-center justify-center">
                                    <i class="text-[#fc7323] text-2xl font-bold">2025</i>
                                </div>
                                <div class="h-20 bg-green-50 rounded-lg border border-green-100 flex items-center justify-center">
                                    <i class="text-[#39a900] text-2xl font-bold">✔</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <footer class="absolute bottom-0 w-full py-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Servicio Nacional de Aprendizaje SENA. Todos los derechos reservados.
        </footer>
    </div>
</body>
</html>
