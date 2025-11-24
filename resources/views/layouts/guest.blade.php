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

        <!-- Styles -->
        @livewireStyles
        <style>
            /* SENA Theme Overrides for Guest Layout */
            :root {
                --sena-orange: #fc7323;
                --sena-green: #39a900;
                --sena-blue: #00324d;
            }
            
            /* Override default Tailwind Indigo used in Jetstream */
            .text-indigo-600, .text-indigo-500, .text-blue-600 { color: var(--sena-orange) !important; }
            .hover\:text-indigo-900:hover { color: #e05d0b !important; }
            
            .bg-indigo-600, .bg-indigo-500, .bg-blue-600 { background-color: var(--sena-orange) !important; }
            .hover\:bg-indigo-700:hover, .hover\:bg-indigo-500:hover { background-color: #e05d0b !important; }
            
            .focus\:border-indigo-500:focus, .focus\:border-indigo-300:focus { border-color: var(--sena-orange) !important; }
            .focus\:ring-indigo-500:focus, .focus\:ring-indigo-200:focus { --tw-ring-color: rgba(252, 115, 35, 0.5) !important; }

            /* Background tweak */
            body { background-color: #f3f4f6; }
            .min-h-screen { background-color: #f3f4f6; }
            
            /* Logo replacement helper if SVG is inline */
            .text-gray-800 { color: var(--sena-blue) !important; }
        </style>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
