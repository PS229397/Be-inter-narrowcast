@pushOnce('styles')
    @vite(['resources/css/app.css'])
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/app.js'])
@endPushOnce

<x-filament-panels::page>
    <div class="mx-auto w-full max-w-[1440px] px-4 py-8 lg:py-16 xl:px-0">
        {{ $this->content }}
    </div>
</x-filament-panels::page>
