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
                <span class="text-xs text-gray-300">Asistencias</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="px-4 pb-4 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 text-xl"></i>
                <span class="mx-4 font-medium">Dashboard</span>
            </a>

            <!-- Estudiantes -->
            <a href="{{ route('students.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <i class="bi bi-people text-xl"></i>
                <span class="mx-4 font-medium">Estudiantes</span>
            </a>

            @if(auth()->user()->canManageData())
            <!-- Grupos/Fichas (Solo Admin/Coordinador) -->
            <a href="{{ route('groups.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('groups.*') ? 'active' : '' }}">
                <i class="bi bi-collection text-xl"></i>
                <span class="mx-4 font-medium">Fichas</span>
            </a>

            <!-- Programas (Solo Admin/Coordinador) -->
            <a href="{{ route('programs.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('programs.*') ? 'active' : '' }}">
                <i class="bi bi-book text-xl"></i>
                <span class="mx-4 font-medium">Programas</span>
            </a>

            <!-- Instructores (Solo Admin/Coordinador) -->
            <a href="{{ route('instructors.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('instructors.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge text-xl"></i>
                <span class="mx-4 font-medium">Instructores</span>
            </a>

            <!-- Asignación de Instructores (Solo Admin/Coordinador) -->
            <a href="{{ route('instructor_assignments.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('instructor_assignments.*') ? 'active' : '' }}">
                <i class="bi bi-person-check text-xl"></i>
                <span class="mx-4 font-medium">Asignar Instructores</span>
            </a>
            @endif

            @if(auth()->user()->canPerformInstructorActions())
            <!-- Asistencias (Solo Admin/Instructor) -->
            <a href="{{ route('attendance.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                <i class="bi bi-calendar-check text-xl"></i>
                <span class="mx-4 font-medium">Inasistencias</span>
            </a>

            <!-- Llamados de Atención (Solo Admin/Instructor) -->
            <a href="{{ route('disciplinary_actions.global_index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('disciplinary_actions.global_index') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle text-xl"></i>
                <span class="mx-4 font-medium">Llamados de Atención</span>
            </a>

            <!-- Registro Masivo (Solo Admin/Instructor) -->
            <a href="{{ route('attendance.bulk.create') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('attendance.bulk.*') ? 'active' : '' }}">
                <i class="bi bi-check2-square text-xl"></i>
                <span class="mx-4 font-medium">Registro Masivo</span>
            </a>

            <!-- Divider Académico -->
            <hr class="my-4 border-gray-600">
            <p class="px-4 text-xs text-gray-400 uppercase">Académico</p>

            <!-- Calificaciones (Solo Admin/Instructor) -->
            <a href="{{ route('grading.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('grading.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check text-xl"></i>
                <span class="mx-4 font-medium">Calificaciones</span>
            </a>
            @endif

            @if(auth()->user()->canManageData() || auth()->user()->isInstructor())
            <!-- Planes de Mejoramiento -->
            <a href="{{ route('improvement_plans.index') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('improvement_plans.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check text-xl"></i>
                <span class="mx-4 font-medium">Planes de Mejoramiento</span>
            </a>
            @endif

            @if(auth()->user()->isStudentRole())
            <!-- Mi Progreso (solo estudiantes) -->
            <a href="{{ route('student.my_progress') }}" 
               class="flex items-center px-4 py-3 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link {{ request()->routeIs('student.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow text-xl"></i>
                <span class="mx-4 font-medium">Mi Progreso</span>
            </a>
            @endif

            <!-- Divider -->
            <hr class="my-4 border-gray-600">

            <!-- User Section -->
            <div class="flex items-center px-4 py-3 mt-2 bg-black/20 rounded-lg">
                <img class="object-cover w-10 h-10 rounded-full border-2 border-[#fc7323]" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                <div class="mx-4 overflow-hidden">
                    <h4 class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</h4>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Profile -->
            <a href="{{ route('profile.show') }}" 
               class="flex items-center px-4 py-2 mt-2 text-gray-300 transition-colors duration-200 transform rounded-md sena-sidebar-link">
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
        <header class="flex items-center justify-between px-6 py-4 bg-white border-b">
            <!-- Hamburger Menu for Mobile -->
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        @isset($header)
                            {{ $header }}
                        @else
                            Sistema de Control de Asistencias
                        @endisset
                    </h2>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600 hidden md:block">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
                
                <!-- User Avatar -->
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-sm focus:outline-none">
                        <img class="object-cover w-8 h-8 rounded-full border-2 border-gray-200" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
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
                         class="absolute right-0 z-10 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg"
                         style="display: none;">
                        <div class="py-1">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#fff4e6] hover:text-[#fc7323] transition-colors">Perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-[#fff4e6] hover:text-[#fc7323] transition-colors">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
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

