@php
    $user = Auth::user();
@endphp

<aside x-data="{ sidebarOpen: false }" class="hidden md:flex md:flex-col w-64 bg-gradient-to-b from-sena-700 to-sena-800 shadow-2xl h-screen fixed left-0 top-0 z-30 transition-all duration-300 ease-in-out">
    <!-- Logo Area -->
    <div class="flex items-center justify-center h-20 px-6 border-b border-sena-600/30 bg-sena-800/50 backdrop-blur-sm">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-white shadow-lg transform hover:scale-110 transition-transform duration-200">
                <img src="{{ asset('images/logoSena.png') }}" alt="SENA Logo" class="w-10 h-10 object-contain">
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-white text-base tracking-wide">SENA</span>
                <span class="text-[10px] text-sena-200 uppercase tracking-wider font-medium">Gestión Académica</span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
        @php
            $navClasses = "flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group relative overflow-hidden";
            $activeClasses = "bg-white/20 text-white shadow-lg backdrop-blur-sm ring-2 ring-white/30";
            $inactiveClasses = "text-sena-100 hover:bg-white/10 hover:text-white";
            
            $isActive = function($routePattern) {
                return request()->routeIs($routePattern) ? true : false;
            };
        @endphp

        <!-- Dashboard -->
        @if($user->isAdmin())
            <a href="{{ route('dashboard') }}" class="{{ $navClasses }} {{ $isActive('dashboard') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center w-full relative z-10">
                    <svg class="w-5 h-5 mr-3 {{ $isActive('dashboard') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-semibold">Dashboard</span>
                </div>
            </a>
        @elseif($user->isCoordinator())
            <a href="{{ route('coordinator.dashboard') }}" class="{{ $navClasses }} {{ $isActive('coordinator.*') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center w-full relative z-10">
                    <svg class="w-5 h-5 mr-3 {{ $isActive('coordinator.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="font-semibold">Panel de Vigilancia</span>
                </div>
            </a>
        @elseif($user->isInstructor())
            <a href="{{ route('instructor.dashboard') }}" class="{{ $navClasses }} {{ $isActive('instructor.*') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center w-full relative z-10">
                    <svg class="w-5 h-5 mr-3 {{ $isActive('instructor.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="font-semibold">Panel del Instructor</span>
                </div>
            </a>
        @endif

        @if($user->canManageAcademicStructure() || $user->isCoordinator())
            <div class="px-4 pt-6 pb-2">
                <p class="text-xs font-bold text-sena-300 uppercase tracking-wider">Académico</p>
            </div>

            <a href="{{ route('students.index') }}" class="{{ $navClasses }} {{ $isActive('students.*') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center w-full relative z-10">
                    <svg class="w-5 h-5 mr-3 {{ $isActive('students.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Estudiantes</span>
                </div>
            </a>

            <a href="{{ route('groups.index') }}" class="{{ $navClasses }} {{ $isActive('groups.*') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center w-full relative z-10">
                    <svg class="w-5 h-5 mr-3 {{ $isActive('groups.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Fichas</span>
                </div>
            </a>

            <a href="{{ route('programs.index') }}" class="{{ $navClasses }} {{ $isActive('programs.*') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center w-full relative z-10">
                    <svg class="w-5 h-5 mr-3 {{ $isActive('programs.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span>Programas</span>
                </div>
            </a>

            <a href="{{ route('instructors.index') }}" class="{{ $navClasses }} {{ $isActive('instructors.*') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center w-full relative z-10">
                    <svg class="w-5 h-5 mr-3 {{ $isActive('instructors.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Instructores</span>
                </div>
            </a>

            <!-- Competencias link removed as per request -->
        @endif

        @if($user->canViewAttendance() || $user->canViewDisciplinaryActions() || !$user->isCoordinator())
            <div class="px-4 pt-6 pb-2">
                <p class="text-xs font-bold text-sena-300 uppercase tracking-wider">Seguimiento</p>
            </div>

            @if($user->canViewAttendance())
                <a href="{{ route('attendance-lists.index') }}" class="{{ $navClasses }} {{ $isActive('attendance-lists.*') ? $activeClasses : $inactiveClasses }}">
                    <div class="flex items-center w-full relative z-10">
                        <svg class="w-5 h-5 mr-3 {{ $isActive('attendance-lists.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <span>Inasistencias</span>
                    </div>
                </a>
            @endif

            @if($user->canViewDisciplinaryActions())
                <a href="{{ route('disciplinary-actions.global-index') }}" class="{{ $navClasses }} {{ $isActive('disciplinary-actions.*') ? $activeClasses : $inactiveClasses }}">
                    <div class="flex items-center w-full relative z-10">
                        <svg class="w-5 h-5 mr-3 {{ $isActive('disciplinary-actions.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span>Llamados de Atención</span>
                    </div>
                </a>
            @endif

            @if(!$user->isCoordinator())
                <a href="{{ route('improvement-plans.index') }}" class="{{ $navClasses }} {{ $isActive('improvement-plans.*') ? $activeClasses : $inactiveClasses }}">
                    <div class="flex items-center w-full relative z-10">
                        <svg class="w-5 h-5 mr-3 {{ $isActive('improvement-plans.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Planes de Mejoramiento</span>
                    </div>
                </a>
            @endif
        @endif

        @if($user->canViewReports() || $user->canViewAudit())
            <div class="px-4 pt-6 pb-2">
                <p class="text-xs font-bold text-sena-300 uppercase tracking-wider">Sistema</p>
            </div>

            @if($user->canViewReports())
                <a href="{{ route('reports.index') }}" class="{{ $navClasses }} {{ $isActive('reports.*') ? $activeClasses : $inactiveClasses }}">
                    <div class="flex items-center w-full relative z-10">
                        <svg class="w-5 h-5 mr-3 {{ $isActive('reports.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Reportes</span>
                    </div>
                </a>
            @endif

            @if($user->canViewAudit())
                <a href="{{ route('audit.index') }}" class="{{ $navClasses }} {{ $isActive('audit.*') ? $activeClasses : $inactiveClasses }}">
                    <div class="flex items-center w-full relative z-10">
                        <svg class="w-5 h-5 mr-3 {{ $isActive('audit.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Auditoría</span>
                    </div>
                </a>
                
                <!-- Modo Mantenimiento -->
                <a href="{{ route('maintenance.index') }}" class="{{ $navClasses }} {{ $isActive('maintenance.*') ? $activeClasses : $inactiveClasses }}">
                    <div class="flex items-center w-full relative z-10">
                        <svg class="w-5 h-5 mr-3 {{ $isActive('maintenance.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Modo Mantenimiento</span>
                    </div>
                </a>
            @endif
        @endif
        
        @if($user->isStudent())
             <div class="px-4 pt-6 pb-2">
                <p class="text-xs font-bold text-sena-300 uppercase tracking-wider">Mi Aprendizaje</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="{{ $navClasses }} {{ $isActive('student.*') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center w-full relative z-10">
                    <svg class="w-5 h-5 mr-3 {{ $isActive('student.*') ? 'text-white' : 'text-sena-200 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Mi Progreso</span>
                </div>
            </a>
        @endif
    </nav>

    <!-- User Profile (Bottom) -->
    <div class="border-t border-sena-600/30 p-4 bg-sena-800/50 backdrop-blur-sm">
        <div class="flex items-center w-full">
            <div class="flex-shrink-0">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-white/30 shadow-lg" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                @else
                    <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center text-sena-700 font-bold text-sm border-2 border-white/30 shadow-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="ml-3 min-w-0 flex-1">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-sena-200 truncate">
                     @php
                        $roleMap = [
                            'admin' => 'Administrador',
                            'coordinator' => 'Coordinador',
                            'instructor' => 'Instructor',
                            'student' => 'Aprendiz',
                        ];
                        $role = Auth::user()->getNormalizedRole();
                    @endphp
                    {{ $roleMap[$role] ?? 'Usuario' }}
                </p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="ml-2 p-1.5 text-sena-200 hover:text-white hover:bg-white/10 rounded-lg transition-colors" title="Cerrar Sesión">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
