{{-- Carousel component input panel --}}
<div x-show="getSelectedLeafComponent() === 'carousel'" class="flex flex-col gap-3">
    <p class="text-xs font-medium text-zinc-400">Carousel</p>

    {{-- Upload zone --}}
    <label class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-[#24242a] py-4 transition hover:border-amber-400/40">
        <svg class="size-6 text-zinc-500" viewBox="0 0 20 20" fill="none">
            <path d="M10 4v8M6 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        <span class="text-xs text-zinc-400">Add images · PNG, JPG, WebP</span>
        <span x-show="uploadProgress.carousel > 0 && uploadProgress.carousel < 100" class="text-xs text-amber-400" x-text="`Uploading… ${uploadProgress.carousel}%`"></span>
        <input type="file" accept="image/*" multiple class="hidden"
            x-on:change="handleMultipleUpload(selectedId, 'images', Array.from($event.target.files), 'carousel'); $event.target.value = ''">
    </label>

    {{-- Thumbnail strip (drag to reorder) --}}
    <div
        x-show="(slideContent[selectedId]?.images ?? []).length > 0"
        class="grid grid-cols-3 gap-2"
        x-on:dragover.prevent
    >
        <template x-for="(img, idx) in (slideContent[selectedId]?.images ?? [])" :key="img">
            <div
                class="group relative cursor-grab overflow-hidden rounded-lg border border-white/10 bg-[#24242a] active:cursor-grabbing"
                style="aspect-ratio:16/9"
                draggable="true"
                x-on:dragstart="carouselDragStart(selectedId, idx)"
                x-on:dragover.prevent="carouselDragOver(idx)"
                x-on:drop.prevent="carouselDrop(selectedId)"
            >
                <img :src="img" class="h-full w-full object-cover" alt="">
                {{-- Index badge --}}
                <span class="absolute left-1.5 top-1.5 rounded bg-black/60 px-1.5 py-0.5 text-[10px] text-white" x-text="idx + 1"></span>
                {{-- Remove --}}
                <button type="button"
                    x-on:click.stop="removeCarouselImage(selectedId, idx)"
                    class="absolute right-1 top-1 hidden size-6 place-items-center rounded border border-red-400/40 bg-red-500/30 text-red-300 group-hover:grid">
                    <svg class="size-3" viewBox="0 0 20 20" fill="none">
                        <path d="M6 6l8 8M14 6l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
                {{-- Drag handle overlay --}}
                <div class="pointer-events-none absolute inset-0 hidden items-center justify-center bg-amber-400/10 group-active:flex">
                    <svg class="size-5 text-amber-400/80" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 4a1 1 0 110-2 1 1 0 010 2zM13 4a1 1 0 110-2 1 1 0 010 2zM7 10a1 1 0 110-2 1 1 0 010 2zM13 10a1 1 0 110-2 1 1 0 010 2zM7 16a1 1 0 110-2 1 1 0 010 2zM13 16a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                </div>
            </div>
        </template>
    </div>

    {{-- Duration --}}
    <div class="flex flex-col gap-1.5">
        <div class="flex items-center justify-between">
            <span class="text-xs font-medium text-zinc-400">Slide duration</span>
            <span class="text-xs text-amber-400" x-text="`${slideContent[selectedId]?.duration ?? 5}s`"></span>
        </div>
        <input type="range" min="1" max="30" step="1"
            :value="slideContent[selectedId]?.duration ?? 5"
            x-on:input="updateContent(selectedId, 'duration', +$event.target.value); $nextTick(() => render())"
            class="w-full accent-amber-400">
        <div class="flex justify-between text-[10px] text-zinc-600">
            <span>1s</span><span>30s</span>
        </div>
    </div>
</div>
