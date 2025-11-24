<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Styles -->
        @livewireStyles
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        
        <style>
            :root {
                --sena-orange: #fc7323;
                --sena-green: #39a900;
                --sena-blue: #00324d;
                --sena-light-gray: #f4f4f4;
            }

            /* Bootstrap Overrides */
            .btn-primary {
                background-color: var(--sena-orange);
                border-color: var(--sena-orange);
            }
            .btn-primary:hover {
                background-color: #e05d0b;
                border-color: #e05d0b;
            }

            .btn-success {
                background-color: var(--sena-green);
                border-color: var(--sena-green);
            }
            .btn-success:hover {
                background-color: #2d8500;
                border-color: #2d8500;
            }

            .text-primary { color: var(--sena-orange) !important; }
            .text-success { color: var(--sena-green) !important; }
            
            .bg-primary { background-color: var(--sena-orange) !important; }
            .bg-success { background-color: var(--sena-green) !important; }
            .bg-dark { background-color: var(--sena-blue) !important; }

            .table-dark {
                --bs-table-bg: var(--sena-blue);
                --bs-table-border-color: #004d7a;
            }

            /* Layout Tweaks */
            body {
                background-color: #f8f9fa;
            }
            
            /* Sidebar Customization (Tailwind override via CSS) */
            .sena-sidebar {
                background-color: var(--sena-blue) !important;
            }
            .sena-sidebar-link:hover, .sena-sidebar-link.active {
                background-color: rgba(252, 115, 35, 0.1) !important;
                color: var(--sena-orange) !important;
                border-left: 4px solid var(--sena-orange);
            }
        </style>
    <body class="font-sans antialiased">
        <x-banner />

        <!-- Sidebar Layout -->
        <x-sidebar>
            <x-slot name="header">
                @if (isset($header))
                    {{ $header }}
                @endif
            </x-slot>
            
            {{ $slot }}
        </x-sidebar>

        @stack('modals')

        @livewireScripts

        <script>
            // SweetAlert2 for Flash Messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}",
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    html: '<ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                });
            @endif

            // SweetAlert2 for Delete Confirmations
            document.addEventListener('DOMContentLoaded', function() {
                const deleteForms = document.querySelectorAll('form[onsubmit^="return confirm"]');
                deleteForms.forEach(form => {
                    form.onsubmit = function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: "¡No podrás revertir esto!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    };
                });
            });
        </script>
    </body>
</html>
