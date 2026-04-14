{{-- Image component input panel --}}
<div x-show="getSelectedLeafComponent() === 'image'" class="flex flex-col gap-3">
    <p class="text-xs font-medium text-zinc-400">Image</p>

    {{-- Preview --}}
    <div
        x-show="slideContent[selectedId]?.url"
        class="relative overflow-hidden rounded-xl border border-white/10"
        style="aspect-ratio:16/9;"
    >
        <img
            x-bind:src="slideContent[selectedId]?.url ?? ''"
            class="h-full w-full object-cover"
            alt="Preview"
        >
        <button
            type="button"
            x-on:click="updateContent(selectedId, 'url', null); $nextTick(() => render())"
            class="absolute right-2 top-2 grid size-7 place-items-center rounded-lg border border-red-400/40 bg-red-500/20 text-red-300 transition hover:bg-red-500/40"
            title="Remove"
        >
            <svg class="size-4" viewBox="0 0 20 20" fill="none">
                <path d="M6 6l8 8M14 6l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    {{-- Upload zone --}}
    <label
        x-show="!slideContent[selectedId]?.url"
        class="flex min-h-[140px] cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-[#24242a] transition hover:border-amber-400/40"
    >
        <svg class="size-7 text-zinc-500" viewBox="0 0 20 20" fill="none">
            <path d="M10 4v8M6 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        <span class="text-xs text-zinc-400">Click to upload · PNG, JPG, WebP</span>
        <span x-show="uploadProgress.image > 0 && uploadProgress.image < 100" class="text-xs text-amber-400" x-text="`Uploading… ${uploadProgress.image}%`"></span>
        <input
            type="file"
            accept="image/*"
            class="hidden"
            x-on:change="handleSingleUpload(selectedId, 'url', $event.target.files[0], 'image')"
        >
    </label>
</div>
