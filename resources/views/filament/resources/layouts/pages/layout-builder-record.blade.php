@pushOnce('styles')
    @vite(['resources/css/app.css'])
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/app.js'])
@endPushOnce

<x-filament-panels::page>
    <div class="mx-auto w-full max-w-[1440px] px-4 py-8 lg:py-16 xl:px-0">
        <div class="mb-4 flex items-center justify-between">
            <h1 x-data class="text-3xl font-bold text-gray-950 dark:text-white">
                {{ $this instanceof \Filament\Resources\Pages\EditRecord ? 'Edit ' : 'Create ' }}<span x-text="$wire.get('data.title') || 'Untitled layout'"></span>
            </h1>
            <div class="flex items-center gap-3">
                <a
                    href="{{ \App\Filament\Admin\Resources\LayoutResource::getUrl('index') }}"
                    class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:text-gray-300 dark:ring-white/20 dark:hover:bg-white/5"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    form="form"
                    class="inline-flex items-center rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-amber-600 focus:outline-none"
                >
                    {{ $this instanceof \Filament\Resources\Pages\EditRecord ? 'Save' : 'Create' }}
                </button>
            </div>
        </div>
        {{ $this->content }}
    </div>
</x-filament-panels::page>
