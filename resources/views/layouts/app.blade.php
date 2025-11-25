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
    @stack('scripts')
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    @auth
        <!-- Layout con Sidebar para usuarios autenticados -->
        <div class="flex h-screen bg-gray-50">
            <!-- Sidebar -->
            <aside class="hidden md:flex md:flex-shrink-0">
                <div class="flex flex-col w-64 shadow-lg" style="background: linear-gradient(180deg, #238276 0%, #1a6b60 100%);">
                    <!-- Logo SENA -->
                    <div class="flex items-center justify-center h-16 px-4 bg-white/10 backdrop-blur-sm">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <span class="font-bold text-xl" style="color: #238276;">S</span>
                            </div>
                            <div class="text-white">
                                <h1 class="font-bold text-sm">SENA</h1>
                                <p class="text-xs text-white/80">Gestión Académica</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navegación -->
                    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                        @php
                            $user = Auth::user();
                        @endphp
                        
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        @if($user->canManageAcademicStructure())
                        <!-- Gestión Académica (Admin y Coordinador) -->
                        <a href="{{ route('students.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('students.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="font-medium">Estudiantes</span>
                        </a>

                        <a href="{{ route('groups.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('groups.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium">Fichas</span>
                        </a>

                        <a href="{{ route('programs.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('programs.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="font-medium">Programas</span>
                        </a>

                        <a href="{{ route('instructors.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('instructors.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium">Instructores</span>
                        </a>

                        <a href="{{ route('competencias.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('competencias.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            <span class="font-medium">Competencias</span>
                        </a>
                        @endif

                        @if($user->canManageAttendance())
                        <!-- Asistencias (Admin, Coordinador e Instructores) -->
                        <a href="{{ route('attendance-lists.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('attendance-lists.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="font-medium">Asistencias</span>
                        </a>
                        @endif

                        @if($user->canGrade())
                        <!-- Calificaciones (Solo Admin e Instructores, NO Coordinador) -->
                        <a href="{{ route('grading.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('grading.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Calificaciones</span>
                        </a>
                        @endif

                        @if($user->canCreateDisciplinaryActions())
                        <!-- Llamados de Atención (Admin, Coordinador e Instructores) -->
                        <a href="{{ route('disciplinary-actions.global-index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('disciplinary-actions.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="font-medium">Llamados de Atención</span>
                        </a>
                        @endif

                        <a href="{{ route('improvement-plans.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('improvement-plans.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium">Planes de Mejoramiento</span>
                        </a>

                        @if($user->canViewReports())
                        <!-- Reportes (Solo Admin y Coordinador) -->
                        <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('reports.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium">Reportes</span>
                        </a>
                        @endif

                        @if($user->canViewAudit())
                        <!-- Auditoría (Solo Admin) -->
                        <a href="{{ route('audit.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('audit.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium">Auditoría</span>
                        </a>
                        @endif

                        @if($user->isStudent())
                        <!-- Portal del Estudiante (Solo Aprendices) -->
                        <a href="{{ route('student.dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('student.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium">Mi Progreso</span>
                        </a>
                        @endif
                    </nav>

                    <!-- Usuario -->
                    <div class="p-4 border-t border-white/20">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                                <span class="font-semibold text-sm" style="color: #238276;">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white font-medium text-sm truncate">{{ Auth::user()->name }}</p>
                                <p class="text-white/70 text-xs truncate">
                                    @php
                                        $roleMap = [
                                            'admin' => 'Administrador',
                                            'coordinator' => 'Coordinador',
                                            'instructor' => 'Instructor',
                                            'student' => 'Aprendiz',
                                        ];
                                        $role = Auth::user()->getNormalizedRole();
                                    @endphp
                                    {{ $roleMap[$role] ?? ucfirst(str_replace('_', ' ', Auth::user()->rol ?? 'Usuario')) }}
                                </p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-white bg-white/10 rounded-lg hover:bg-white/20 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Contenido Principal -->
            <div class="flex flex-col flex-1 overflow-hidden">
                <!-- Top Bar -->
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="flex items-center justify-between h-16 px-6">
                        <div class="flex items-center">
                            <button class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <h2 class="ml-4 md:ml-0 text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-full relative">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Contenido -->
                <main class="flex-1 overflow-y-auto bg-gray-50">
                    <div class="p-6">
                        @yield('content')
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
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: #238276;">
                                    <span class="text-white font-bold text-xl">S</span>
                                </div>
                                <div>
                                    <h1 class="text-xl font-bold text-gray-900">SENA</h1>
                                    <p class="text-xs text-gray-500">Gestión Académica</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-white rounded-lg transition-colors font-medium" style="background-color: #fc7323;" onmouseover="this.style.backgroundColor='#e8651f'" onmouseout="this.style.backgroundColor='#fc7323'">
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
    @stack('scripts')
</body>
</html>
