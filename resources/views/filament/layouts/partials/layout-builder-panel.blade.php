<aside
    class="{{ $asideClass }}"
    x-on:click.stop
>
    <div x-data="{ baseOpen: true }" class="flex flex-1 flex-col gap-4 overflow-y-auto p-4">
        <section class="rounded-xl border border-white/8 bg-[#111114]">
            <button
                type="button"
                x-on:click="baseOpen = ! baseOpen"
                class="flex w-full items-center justify-between px-3 py-3 text-left"
            >
                <span class="text-sm font-medium text-zinc-300">Base components</span>
                <svg
                    class="size-4 text-zinc-500 transition"
                    x-bind:style="baseOpen ? 'transform: rotate(180deg);' : ''"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                </svg>
            </button>

            <div x-cloak x-show="baseOpen" class="space-y-3 border-t border-white/8 px-3 pb-3 pt-3">
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
                <p class="rounded-xl border border-white/8 bg-[#16161b] px-3 py-2 text-xs text-zinc-500">
                    Select a section on the canvas, then choose a base component.
                </p>
            </div>
        </section>

        <section class="rounded-xl border border-white/8 bg-[#111114] p-3">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-sm font-medium text-zinc-300">Custom components</p>
                <span class="text-[11px] uppercase tracking-[0.08em] text-zinc-500">{{ count($customComponents) }}</span>
            </div>

            @if (count($customComponents))
                <div class="space-y-2">
                    @foreach ($customComponents as $component)
                        <button
                            data-component="{{ $component['key'] }}"
                            type="button"
                            x-on:click.stop="assignComponent(@js($component['key']))"
                            class="flex w-full items-center gap-3 rounded-xl border border-white/10 bg-[#24242a] px-3 py-3 text-left text-zinc-300 transition hover:border-amber-400/40 hover:text-amber-300"
                        >
                            <svg class="size-5 shrink-0" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M4 8.5A2.5 2.5 0 0 1 6.5 6H8V4.5A1.5 1.5 0 0 1 9.5 3h1A1.5 1.5 0 0 1 12 4.5V6h1.5A2.5 2.5 0 0 1 16 8.5v1A2.5 2.5 0 0 1 13.5 12H12v1.5A1.5 1.5 0 0 1 10.5 15h-1A1.5 1.5 0 0 1 8 13.5V12H6.5A2.5 2.5 0 0 1 4 9.5v-1Z" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
                                <path d="M8 9h4M10 7v4" stroke="currentColor" stroke-width="1.25" stroke-linecap="round"/>
                            </svg>
                            <div class="min-w-0">
                                <div class="truncate text-sm font-medium">{{ $component['title'] }}</div>
                                @if (filled($component['customer'] ?? null))
                                    <div class="truncate text-xs text-zinc-500">{{ $component['customer'] }}</div>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            @else
                <p class="rounded-xl border border-dashed border-white/10 bg-[#16161b] px-3 py-3 text-xs text-zinc-500">
                    No custom components available for the current customer selection.
                </p>
            @endif
        </section>

        <p
            x-show="selectedId"
            class="rounded-xl border border-white/8 bg-[#111114] px-3 py-2 text-xs text-zinc-500"
            x-text="`Selected section: ${selectedId}`"
        ></p>
    </div>
</aside>
