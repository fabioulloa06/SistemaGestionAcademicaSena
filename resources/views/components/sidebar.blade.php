@php
    $user = Auth::user();
@endphp

<div x-data="{ sidebarOpen: true }" class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
         class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition-transform duration-300 transform sena-sidebar lg:translate-x-0 lg:static lg:inset-0">
        
        <!-- Logo -->
        <div class="flex items-center justify-center mt-8 mb-8">
            <div class="flex flex-col items-center">
                <div class="bg-white p-3 rounded-lg mb-2">
                    <img src="{{ asset('images/logoSena.png') }}" alt="SENA Logo" class="w-16 h-16 object-contain">
                </div>
                <span class="mx-2 text-xl font-bold text-white">SENA Control</span>
                <span class="text-xs text-gray-300">Gestión Académica</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="px-4 pb-4 space-y-2">
            <!-- Dashboard - Todos los usuarios -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 text-xl"></i>
                <span class="mx-4 font-medium">Dashboard</span>
            </a>

            @if($user->canManageAcademicStructure())
            <!-- Gestión Académica (Solo Admin y Coordinador) -->
            <a href="{{ route('students.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <i class="bi bi-people text-xl"></i>
                <span class="mx-4 font-medium">Estudiantes</span>
            </a>

            <a href="{{ route('groups.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('groups.*') ? 'active' : '' }}">
                <i class="bi bi-collection text-xl"></i>
                <span class="mx-4 font-medium">Fichas</span>
            </a>

            <a href="{{ route('programs.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('programs.*') ? 'active' : '' }}">
                <i class="bi bi-book text-xl"></i>
                <span class="mx-4 font-medium">Programas</span>
            </a>

            <a href="{{ route('instructors.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('instructors.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge text-xl"></i>
                <span class="mx-4 font-medium">Instructores</span>
            </a>

            <a href="{{ route('competencias.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('programs.*') || request()->routeIs('competencias.*') ? 'active' : '' }}">
                <i class="bi bi-award text-xl"></i>
                <span class="mx-4 font-medium">Competencias</span>
            </a>

            <a href="{{ route('instructor-assignments.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('instructor-assignments.*') ? 'active' : '' }}">
                <i class="bi bi-person-check text-xl"></i>
                <span class="mx-4 font-medium">Asignar Instructores</span>
            </a>
            @endif

            @if($user->canManageAttendance())
            <!-- Asistencias (Admin, Coordinador e Instructores) -->
            <hr class="my-4 border-gray-600">
            <p class="px-4 text-xs text-gray-400 uppercase">Asistencias</p>
            
            <a href="{{ route('attendance-lists.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('attendance-lists.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check text-xl"></i>
                <span class="mx-4 font-medium">Lista de Asistencias</span>
            </a>

            <a href="{{ route('attendance-lists.create') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('attendance-lists.create') ? 'active' : '' }}">
                <i class="bi bi-check2-square text-xl"></i>
                <span class="mx-4 font-medium">Registrar Asistencia</span>
            </a>
            @endif


            @if($user->canCreateDisciplinaryActions())
            <!-- Llamados de Atención (Admin, Coordinador e Instructores) -->
            <a href="{{ route('disciplinary-actions.global-index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('disciplinary-actions.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle text-xl"></i>
                <span class="mx-4 font-medium">Llamados de Atención</span>
            </a>
            @endif

            <!-- Planes de Mejoramiento (Todos los roles autorizados) -->
            <a href="{{ route('improvement-plans.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('improvement-plans.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check text-xl"></i>
                <span class="mx-4 font-medium">Planes de Mejoramiento</span>
            </a>

            @if($user->canViewReports())
            <!-- Reportes (Solo Admin y Coordinador) -->
            <hr class="my-4 border-gray-600">
            <p class="px-4 text-xs text-gray-400 uppercase">Reportes</p>
            
            <a href="{{ route('reports.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph text-xl"></i>
                <span class="mx-4 font-medium">Reportes</span>
            </a>
            @endif

            @if($user->canViewAudit())
            <!-- Auditoría (Solo Admin) -->
            <a href="{{ route('audit.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                <i class="bi bi-shield-check text-xl"></i>
                <span class="mx-4 font-medium">Auditoría</span>
            </a>
            @endif

            @if($user->isStudent())
            <!-- Portal del Estudiante (Solo Aprendices) -->
            <hr class="my-4 border-gray-600">
            <p class="px-4 text-xs text-gray-400 uppercase">Mi Portal</p>
            
            <a href="{{ route('student.dashboard') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('student.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow text-xl"></i>
                <span class="mx-4 font-medium">Mi Progreso</span>
            </a>
            @endif

            <!-- Divider -->
            <hr class="my-4 border-gray-600">

            <!-- User Section -->
            <div class="flex items-center px-4 py-3 mt-2 bg-black/20 rounded-lg">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                    <span class="font-bold text-sm" style="color: #238276;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div class="mx-4 overflow-hidden flex-1">
                    <h4 class="text-sm font-medium text-white truncate">{{ $user->name }}</h4>
                    <p class="text-xs text-gray-400 truncate">
                        @php
                            $roleMap = [
                                'admin' => 'Administrador',
                                'coordinator' => 'Coordinador',
                                'instructor' => 'Instructor',
                                'student' => 'Aprendiz',
                            ];
                            $role = $user->getNormalizedRole();
                        @endphp
                        {{ $roleMap[$role] ?? 'Usuario' }}
                    </p>
                </div>
            </div>

            <!-- Profile -->
            <a href="{{ route('profile.show') }}" 
               class="flex items-center px-4 py-2 mt-2 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link hover:text-white">
                <i class="bi bi-person-gear text-xl"></i>
                <span class="mx-4 font-medium text-sm">Perfil</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <button type="submit"
                        class="flex items-center w-full px-4 py-2 mt-2 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link hover:text-red-400">
                    <i class="bi bi-box-arrow-right text-xl"></i>
                    <span class="mx-4 font-medium text-sm">Cerrar Sesión</span>
                </button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 overflow-hidden">
        <!-- Top Navigation Bar -->
        <header class="flex items-center justify-between px-6 py-4 bg-white border-b shadow-sm">
            <!-- Hamburger Menu for Mobile -->
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden p-2 rounded-md hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        @isset($header)
                            {{ $header }}
                        @else
                            Sistema de Gestión Académica SENA
                        @endisset
                    </h2>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600 hidden md:block">
                    {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </span>
                
                <!-- User Avatar -->
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-sm focus:outline-none">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center border-2 border-gray-300">
                            <span class="font-semibold text-xs text-gray-700">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="userMenuOpen" 
                         @click.away="userMenuOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 z-10 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg border border-gray-200"
                         style="display: none;">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="bi bi-person-gear mr-2"></i>Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="bi bi-box-arrow-right mr-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
            <div class="container mx-auto px-6 py-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Overlay for mobile -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-20 bg-black opacity-50 lg:hidden"
         style="display: none;"></div>
</div>
