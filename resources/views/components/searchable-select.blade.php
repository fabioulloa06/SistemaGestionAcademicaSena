@props(['allowEmpty' => true, 'placeholder' => 'Seleccionar...', 'value' => null])

<div wire:ignore x-data="{
    tomSelectInstance: null,
    initTomSelect() {
        if (this.tomSelectInstance) return;
        
        this.tomSelectInstance = new TomSelect(this.$refs.select, {
            create: false,
            sortField: {
                field: 'text',
                direction: 'asc'
            },
            placeholder: '{{ $placeholder }}',
            allowEmptyOption: {{ $allowEmpty ? 'true' : 'false' }},
            plugins: ['clear_button'],
            onChange: (value) => {
                this.$el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        
        // Sync initial value if provided
        @if($value)
            this.tomSelectInstance.setValue('{{ $value }}');
        @endif

        // Watch for external changes
        this.$watch('value', value => {
            if (value !== this.tomSelectInstance.getValue()) {
                this.tomSelectInstance.setValue(value);
            }
        });
    }
}" x-init="initTomSelect()" class="w-full">
    <select x-ref="select" {{ $attributes->merge(['class' => 'hidden']) }}>
        {{ $slot }}
    </select>
</div>
