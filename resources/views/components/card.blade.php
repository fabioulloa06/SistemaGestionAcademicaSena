@props([
    'titulo' => null,
    'subtitulo' => null,
    'acciones' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-lg overflow-hidden']) }}>
    @if($titulo || $acciones)
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                @if($titulo)
                    <h3 class="text-lg font-semibold text-gray-900">{{ $titulo }}</h3>
                @endif
                @if($subtitulo)
                    <p class="text-sm text-gray-600 mt-1">{{ $subtitulo }}</p>
                @endif
            </div>
            @if($acciones)
                <div class="flex items-center space-x-2">
                    {{ $acciones }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
</div>

