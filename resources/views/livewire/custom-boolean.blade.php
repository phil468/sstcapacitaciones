<!-- resources/views/livewire/custom-boolean.blade.php -->
<div>
    <div wire:click="updateBooleanField('{{ $modelId }}', '{{ $field }}', '{{ $value ? 0 : 1 }}')"
        class="cursor-pointer">
        @if ($value)
            <svg class="w-5 h-5 mx-auto text-green-600 stroke-current" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        @else
            <svg class="w-5 h-5 mx-auto text-red-300 stroke-current" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        @endif
    </div>
    <div class="mt-1 text-sm font-italic" x-data="{ shown: false, fieldId: '{{ $modelId }}.{{ $field }}', value: {{ $value ? 1 : 0 }} }"
        :class="{ 'text-green-600': value == 1, 'text-red-500': value == 0 }"
        x-show.transition.opacity.out.duration.1500ms="shown" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" x-init="@this.on('fieldUpdated', (event, newValue) => { if (event === fieldId) { shown = true;
                value = newValue;
                setTimeout(() => shown = false, 2000); } })" style="display: none">
        Actualizado
    </div>
</div>
