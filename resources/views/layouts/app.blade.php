<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistema de Gestión Académica SENA')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    @auth
        <div x-data="{ open: false }" class="min-h-screen bg-gray-50">
            <!-- Sidebar Component -->
            <x-app-sidebar />

            <!-- Main Content Area -->
            <div class="flex flex-col md:pl-64 min-h-screen transition-all duration-300 ease-in-out">
                <!-- Navbar Component -->
                <x-app-navbar />

                <!-- Page Content -->
                <main class="flex-1 pt-20 pb-8 px-4 sm:px-6 lg:px-8">
                    @if (isset($header))
                        <header class="bg-white shadow rounded-lg mb-6 p-4 sm:px-6">
                            {{ $header }}
                        </header>
                    @endif

                    <!-- Flash Messages - Se manejan con SweetAlert2 -->

                    <!-- Content -->
                    <div class="fade-in-up">
                        @yield('content')
                        {{ $slot ?? '' }}
                    </div>
                </main>
            </div>
        </div>
    @else
        <!-- Layout simple para páginas públicas -->
        <div class="min-h-screen bg-gray-50">
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="flex items-center space-x-2">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: #4d8e37;">
                                    <span class="text-white font-bold text-xl">S</span>
                                </div>
                                <div>
                                    <h1 class="text-xl font-bold text-gray-900">SENA</h1>
                                    <p class="text-xs text-gray-500">Gestión Académica</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-white rounded-lg transition-colors font-medium bg-orange-500 hover:bg-orange-600">
                                Iniciar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
            <main>
                @yield('content')
            </main>
        </div>
    @endauth

    @stack('modals')
    @stack('scripts')
    
    <!-- SweetAlert2 para Flash Messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: {!! json_encode(session('success')) !!},
                    confirmButtonColor: '#4d8e37',
                    confirmButtonText: 'Aceptar',
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            // Error messages
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: {!! json_encode(session('error')) !!},
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Aceptar'
                });
            @endif

            // Warning messages
            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    html: {!! json_encode(session('warning')) !!},
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Aceptar',
                    timer: 7000,
                    timerProgressBar: true
                });
            @endif

            // Info messages
            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: {!! json_encode(session('info')) !!},
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Aceptar',
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            // Status messages (también como success)
            @if(session('status'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: {!! json_encode(session('status')) !!},
                    confirmButtonColor: '#4d8e37',
                    confirmButtonText: 'Aceptar',
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            // Validation errors
            @if($errors->any())
                let errorMessages = '<ul class="text-left list-disc list-inside">';
                @foreach($errors->all() as $error)
                    errorMessages += '<li>{{ $error }}</li>';
                @endforeach
                errorMessages += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Errores de Validación',
                    html: errorMessages,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Aceptar'
                });
            @endif
        });

        // Función global para confirmaciones con SweetAlert
        window.confirmAction = function(message, formId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: message || 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed && formId) {
                    document.getElementById(formId).submit();
                }
            });
            return false;
        };

        // Interceptar todos los formularios de eliminación con data-confirm-message
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form[data-confirm-message]').forEach(function(form) {
                const submitHandler = function(e) {
                    e.preventDefault();
                    const message = form.getAttribute('data-confirm-message') || '¿Estás seguro de realizar esta acción?';
                    
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Sí, continuar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Remover el event listener y enviar el formulario
                            form.removeEventListener('submit', submitHandler);
                            form.submit();
                        }
                    });
                };
                
                form.addEventListener('submit', submitHandler);
            });
        });
    </script>
    
    <style>
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
</body>
</html>
