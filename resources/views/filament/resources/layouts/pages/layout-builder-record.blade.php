@pushOnce('styles')
    @vite(['resources/css/app.css'])
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/app.js'])
@endPushOnce

<x-filament-panels::page>
    <div
        class="lb-page-container mx-auto mt-5 w-full px-4 py-8 [container-type:inline-size] [container-name:layout-page] lg:mt-0 lg:py-16 xl:px-0 lg:[--lb-page-width:min(var(--lb-page-max-width),calc(100vw-var(--lb-page-min-left-clearance)-var(--lb-page-min-right-clearance)))] lg:w-[min(100%,var(--lb-page-width))] lg:max-w-[var(--lb-page-width)] lg:ml-[max(var(--lb-page-min-left-clearance),calc((100vw-var(--lb-page-width))/2))] lg:mr-auto"
        style="--lb-page-max-width: 1440px; --lb-page-min-left-clearance: 320px; --lb-page-min-right-clearance: 40px;"
    >
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
