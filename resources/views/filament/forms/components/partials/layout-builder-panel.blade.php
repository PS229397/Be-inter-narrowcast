<aside
    class="{{ $asideClass }}"
    x-on:click.stop
>
    <div x-show="viewMode === 'admin'" class="flex flex-1 flex-col gap-4 overflow-y-auto p-4">
        <p class="text-sm font-medium text-zinc-400">Base components</p>
        <div class="grid grid-cols-3 gap-2">
            @foreach([
                ['text',      'Text',      '<path d="M5 5h10M10 5v10M7 15h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                ['image',     'Image',     '<rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/><circle cx="7.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M2 13.5l4-4 3 3 2.5-2.5 4.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                ['video',     'Video',     '<rect x="2" y="5" width="12" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M14 8.5l4-2v5l-4-2V8.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>'],
                ['carousel',  'Carousel',  '<rect x="1" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1.5" opacity="0.4"/><rect x="6" y="3" width="8" height="11" rx="1.5" stroke="currentColor" stroke-width="1.5"/><rect x="15" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1.5" opacity="0.4"/><circle cx="8.5" cy="17.5" r="0.75" fill="currentColor"/><circle cx="10" cy="17.5" r="0.75" fill="currentColor"/><circle cx="11.5" cy="17.5" r="0.75" fill="currentColor"/>'],
                ['ticker',    'Ticker',    '<rect x="2" y="7" width="16" height="6" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M5 10h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M13 8.5l2.5 1.5-2.5 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                ['clock',     'Clock',     '<circle cx="10" cy="10" r="7.5" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                ['weather',   'Weather',   '<circle cx="10" cy="8.5" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M10 3v1M10 14v1M3.5 8.5h1M15.5 8.5h1M5.6 4.6l.7.7M13.7 4.6l-.7.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M5 16a3 3 0 010-6h.5a4 4 0 017 0H13a3 3 0 010 6H5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>'],
                ['countdown', 'Countdown', '<circle cx="10" cy="11" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M10 8v3l-2.5 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 3h4M10 1v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>'],
                ['qr',        'QR Code',   '<rect x="3" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/><rect x="12" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/><rect x="3" y="12" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/><rect x="4.5" y="4.5" width="2" height="2" fill="currentColor"/><rect x="13.5" y="4.5" width="2" height="2" fill="currentColor"/><rect x="4.5" y="13.5" width="2" height="2" fill="currentColor"/><path d="M12 12h2v2h-2zM14 14h2v2h-2zM12 16h2M16 12v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
            ] as [$type, $label, $icon])
                <button
                    data-component="{{ $type }}"
                    type="button"
                    x-on:click.stop="assignComponent('{{ $type }}')"
                    class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-[#24242a] text-zinc-400 transition hover:border-amber-400/40 hover:text-amber-300"
                >
                    <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">{!! $icon !!}</svg>
                    <span class="text-xs">{{ $label }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <div x-show="viewMode === 'customer'" class="flex flex-1 flex-col gap-2 overflow-y-auto p-4">
        <p x-show="!selectedId" class="text-sm text-zinc-500">
            Select a section in the layout to edit its content.
        </p>

        <p x-show="selectedId && !getSelectedLeafComponent()" class="text-sm text-zinc-500">
            No component assigned. Switch to Admin view to assign one.
        </p>

        @include('filament.forms.components.base-components.text')
        @include('filament.forms.components.base-components.image')
        @include('filament.forms.components.base-components.video')
        @include('filament.forms.components.base-components.carousel')
        @include('filament.forms.components.base-components.ticker')
        @include('filament.forms.components.base-components.clock')
        @include('filament.forms.components.base-components.weather')
        @include('filament.forms.components.base-components.countdown')
        @include('filament.forms.components.base-components.qr')
    </div>

    <div class="shrink-0 border-t border-white/8 p-4">
        <div class="flex gap-2">
            <button type="button"
                data-view-toggle
                :data-active="viewMode === 'admin' ? 'true' : 'false'"
                x-on:click="viewMode = 'admin'; render()"
                class="h-9 flex-1 rounded-xl border text-sm font-medium transition">
                Admin
            </button>
            <button type="button"
                data-view-toggle
                :data-active="viewMode === 'customer' ? 'true' : 'false'"
                x-on:click="viewMode = 'customer'; render()"
                class="h-9 flex-1 rounded-xl border text-sm font-medium transition">
                Customer
            </button>
        </div>
    </div>
</aside>
