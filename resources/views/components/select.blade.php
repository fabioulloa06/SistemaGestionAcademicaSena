@props([
    'label' => null,
    'name' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'error' => null,
    'help' => null,
    'placeholder' => 'Seleccione una opción',
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-offset-0 transition-colors' . ($error ? ' border-red-500 focus:border-red-500 focus:ring-red-500' : ' focus:border-[#4d8e37] focus:ring-[#4d8e37]')]) }}
        @if($required) required @endif
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $key => $option)
            <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif

    @if($help && !$error)
        <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>

