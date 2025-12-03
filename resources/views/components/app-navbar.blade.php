<nav class="bg-white border-b border-gray-200 fixed w-full z-20 top-0 left-0 md:pl-64 transition-all duration-300 ease-in-out shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side: Mobile Menu Button & Page Title -->
            <div class="flex items-center">
                <button @click="open = !open" class="md:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <div class="hidden md:flex items-center ml-4">
                    <h2 class="text-xl font-bold text-gray-800 tracking-tight">
                        @yield('page-title', 'Dashboard')
                    </h2>
                </div>
            </div>

            <!-- Right Side: Notifications & Profile -->
            <div class="flex items-center space-x-4">
                <!-- Date Display -->
                <div class="hidden lg:flex items-center px-4 py-2 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</span>
                </div>

                <!-- Notifications -->
                <button class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </button>

                <!-- Profile Dropdown -->
                <div class="ml-3 relative" x-data="{ dropdownOpen: false }">
                    <div>
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sena-500 transition-all hover:ring-2 hover:ring-sena-200">
                            <span class="sr-only">Open user menu</span>
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img class="h-9 w-9 rounded-full object-cover border-2 border-gray-200" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            @else
                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-sena-500 to-sena-600 flex items-center justify-center text-white font-bold text-sm border-2 border-gray-200 shadow-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </button>
                    </div>

                    <!-- Dropdown Menu -->
                    <div x-show="dropdownOpen" 
                         @click.away="dropdownOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-lg py-2 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 border border-gray-100"
                         style="display: none;">
                        
                        <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-sena-50 to-sena-100/50">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600 truncate mt-1">{{ Auth::user()->email }}</p>
                            <span class="inline-block mt-2 px-2 py-1 rounded-md text-xs font-medium bg-sena-600 text-white">
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
                            </span>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Perfil
                            </a>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <a href="{{ route('api-tokens.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                    API Tokens
                                </a>
                            @endif

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu (Sidebar Overlay) -->
    <div x-show="open" class="fixed inset-0 flex z-40 md:hidden" role="dialog" aria-modal="true" style="display: none;">
        <div x-show="open" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-600 bg-opacity-75" 
             @click="open = false"
             aria-hidden="true"></div>

        <div x-show="open" 
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative flex-1 flex flex-col max-w-xs w-full bg-white shadow-xl">
            
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button @click="open = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Sidebar Content -->
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-gradient-to-br from-sena-600 to-sena-700 shadow-sm mr-3">
                        <span class="text-white font-bold text-lg">S</span>
                    </div>
                    <span class="font-bold text-gray-800 text-lg">SENA</span>
                </div>
                <nav class="mt-5 px-2 space-y-1">
                    <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-lg text-gray-900 hover:bg-gray-50">
                        <svg class="mr-4 h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                </nav>
            </div>
        </div>
        
        <div class="flex-shrink-0 w-14" aria-hidden="true">
            <!-- Force sidebar to shrink to fit close icon -->
        </div>
    </div>
</nav>
