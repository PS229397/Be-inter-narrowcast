<section
    class="{{ $sectionClass }}"
    x-on:click="if (!isDragging) { selectedId = null; render(); }"
>
    <div class="{{ $innerClass }}">
        <div
            style="{{ $stageStyle }}"
            class="{{ $stageClass }}"
        >
            <div x-ref="gridContainer" wire:ignore class="absolute inset-0"></div>
        </div>
    </div>

    <div x-ref="canvasOverlay" class="pointer-events-none absolute bottom-5 right-5 z-20 hidden">
        <div class="pointer-events-auto flex gap-2.5">
            <button x-ref="btnSliceH" type="button" title="Split horizontally"
                x-on:click.stop="if (selectedId) slice(selectedId, 'h')"
                class="grid size-10 place-items-center rounded-lg border border-amber-400/40 bg-[#1c1c21]/95 text-slate-100 shadow-lg transition hover:border-amber-300 hover:text-amber-300">
                <svg class="size-5" viewBox="0 0 20 20" fill="none">
                    <path d="M3 10H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 3"/>
                </svg>
            </button>
            <button x-ref="btnSliceV" type="button" title="Split vertically"
                x-on:click.stop="if (selectedId) slice(selectedId, 'v')"
                class="grid size-10 place-items-center rounded-lg border border-amber-400/40 bg-[#1c1c21]/95 text-slate-100 shadow-lg transition hover:border-amber-300 hover:text-amber-300">
                <svg class="size-5" viewBox="0 0 20 20" fill="none">
                    <path d="M10 3V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 3"/>
                </svg>
            </button>
            <button x-ref="btnDelete" type="button" title="Delete section"
                x-on:click.stop="if (selectedId) deleteNode(selectedId)"
                class="grid size-10 place-items-center rounded-lg border border-red-400/40 bg-red-500/10 text-red-300 shadow-lg transition hover:border-red-300 hover:bg-red-500/20 hover:text-red-200">
                <svg class="size-5" viewBox="0 0 20 20" fill="none">
                    <path d="M7.5 2.75H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M3.75 5.25H16.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M5.75 5.25L6.45 15.1C6.52 16.06 7.31 16.8 8.27 16.8H11.73C12.69 16.8 13.48 16.06 13.55 15.1L14.25 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.5 8.25V13.25M11.5 8.25V13.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
            <button type="button" title="Clear canvas"
                x-on:click.stop="clearCanvas()"
                class="grid size-10 place-items-center rounded-lg border border-red-400/40 bg-red-500/10 text-red-300 shadow-lg transition hover:border-red-300 hover:bg-red-500/20 hover:text-red-200">
                <svg class="size-5" viewBox="0 0 20 20" fill="none">
                    <g opacity="0.8">
                        <path d="M3 5V2.75H6.25M8.5 2.75H11M13.25 2.75H15.75V5M3 7.5V10.25M3 12.75V15.25H5.5M8 15.25H10.25M15.75 7.5V8.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <path d="M12.25 7.75H14.75M10.5 9.75H16.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M11.4 9.75L11.8 16.1C11.84 16.69 12.34 17.15 12.93 17.15H14.07C14.66 17.15 15.16 16.69 15.2 16.1L15.6 9.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.1 11.5V14.6M13.9 11.5V14.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>
</section>
