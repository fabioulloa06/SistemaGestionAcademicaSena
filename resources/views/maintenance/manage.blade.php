<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Modo Mantenimiento') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Estado Actual -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-gradient-to-r {{ $maintenance['enabled'] ? 'from-red-50 to-red-100 border-red-200' : 'from-green-50 to-green-100 border-green-200' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($maintenance['enabled'])
                                    <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                @else
                                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Estado: 
                                    <span class="{{ $maintenance['enabled'] ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $maintenance['enabled'] ? 'MODO MANTENIMIENTO ACTIVO' : 'Sistema Operativo' }}
                                    </span>
                                </h3>
                                @if($maintenance['enabled'] && isset($maintenance['activated_at']))
                                    <p class="text-sm text-gray-600 mt-1">
                                        Activado el: {{ \Carbon\Carbon::parse($maintenance['activated_at'])->format('d/m/Y H:i:s') }}
                                        @if(isset($maintenance['activated_by_name']))
                                            por {{ $maintenance['activated_by_name'] }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $maintenance['enabled'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $maintenance['enabled'] ? '🔴 ACTIVO' : '🟢 INACTIVO' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @if($maintenance['enabled'])
                <!-- Desactivar Mantenimiento -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Desactivar Modo Mantenimiento
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Al desactivar el modo mantenimiento, todos los usuarios podrán acceder al sistema nuevamente.
                        </p>
                        <form action="{{ route('maintenance.disable') }}" method="POST" onsubmit="event.preventDefault(); Swal.fire({title: '¿Desactivar modo mantenimiento?', text: 'Todos los usuarios podrán acceder al sistema nuevamente.', icon: 'question', showCancelButton: true, confirmButtonColor: '#10b981', cancelButtonColor: '#6b7280', confirmButtonText: 'Sí, desactivar', cancelButtonText: 'Cancelar'}).then((result) => { if (result.isConfirmed) { this.submit(); } }); return false;">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Desactivar Modo Mantenimiento
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Activar Mantenimiento -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Activar Modo Mantenimiento
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Al activar el modo mantenimiento, solo los administradores podrán acceder al sistema. Todos los demás usuarios verán una página de mantenimiento.
                        </p>
                        <form action="{{ route('maintenance.enable') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mensaje para los usuarios <span class="text-gray-500 text-xs">(Opcional)</span>
                                    </label>
                                    <textarea name="message" 
                                              id="message" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                              placeholder="Ej: Estamos realizando actualizaciones del sistema. El servicio estará disponible en breve.">{{ $maintenance['message'] ?? '' }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Mensaje personalizado que verán los usuarios en la página de mantenimiento.</p>
                                </div>
                                <div>
                                    <label for="estimated_time" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tiempo Estimado <span class="text-gray-500 text-xs">(Opcional)</span>
                                    </label>
                                    <input type="text" 
                                           name="estimated_time" 
                                           id="estimated_time"
                                           value="{{ $maintenance['estimated_time'] ?? '' }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                           placeholder="Ej: 30 minutos, 1 hora, etc.">
                                    <p class="text-xs text-gray-500 mt-1">Tiempo estimado de duración del mantenimiento.</p>
                                </div>
                                <div>
                                    <button type="submit" 
                                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            onclick="return confirm('¿Estás seguro de activar el modo mantenimiento? Solo los administradores podrán acceder al sistema.')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        Activar Modo Mantenimiento
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Información -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Información Importante</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Cuando el modo mantenimiento está activo, solo los administradores pueden acceder al sistema.</li>
                                <li>Los usuarios regulares verán una página de mantenimiento informativa.</li>
                                <li>Puedes desactivar el modo mantenimiento en cualquier momento desde esta página.</li>
                                <li>El estado se guarda en un archivo JSON en el servidor.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

