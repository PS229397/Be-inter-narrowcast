@once
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endonce

<x-filament-panels::page>
    <div class="mx-auto w-full max-w-[1440px] px-4 py-8 lg:py-16 xl:px-0">
        <div
            wire:key="layout-builder-resource-form-{{ $this->record?->getKey() ?? 'new' }}-{{ data_get($this->data, 'orientation', 'landscape') }}"
        >
            {{ $this->form }}
        </div>
    </div>
</x-filament-panels::page>
