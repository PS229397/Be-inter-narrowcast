{{-- Video component input panel --}}
<div x-show="getSelectedLeafComponent() === 'video'" class="flex flex-col gap-3">
    <p class="text-xs font-medium text-zinc-400">Video</p>

    {{-- Source tabs --}}
    <div class="flex rounded-xl border border-white/10 bg-[#24242a] p-1">
        <button type="button"
            x-on:click="updateContent(selectedId, 'sourceTab', 'file')"
            :class="(slideContent[selectedId]?.sourceTab ?? 'file') === 'file'
                ? 'bg-[#1c1c21] text-white shadow-sm'
                : 'text-zinc-500 hover:text-zinc-300'"
            class="flex-1 rounded-lg py-1.5 text-xs font-medium transition">
            File upload
        </button>
        <button type="button"
            x-on:click="updateContent(selectedId, 'sourceTab', 'yt')"
            :class="(slideContent[selectedId]?.sourceTab ?? 'file') === 'yt'
                ? 'bg-[#1c1c21] text-white shadow-sm'
                : 'text-zinc-500 hover:text-zinc-300'"
            class="flex-1 rounded-lg py-1.5 text-xs font-medium transition">
            YouTube
        </button>
    </div>

    {{-- File upload tab --}}
    <div x-show="(slideContent[selectedId]?.sourceTab ?? 'file') === 'file'">
        <div x-show="slideContent[selectedId]?.fileUrl" class="relative mb-2 overflow-hidden rounded-xl border border-white/10">
            <video
                x-bind:src="slideContent[selectedId]?.fileUrl ?? ''"
                controls class="w-full rounded-xl" style="max-height:160px; object-fit:cover;"
            ></video>
            <button type="button"
                x-on:click="updateContent(selectedId, 'fileUrl', null); $nextTick(() => render())"
                class="absolute right-2 top-2 grid size-7 place-items-center rounded-lg border border-red-400/40 bg-red-500/20 text-red-300 transition hover:bg-red-500/40">
                <svg class="size-4" viewBox="0 0 20 20" fill="none">
                    <path d="M6 6l8 8M14 6l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <label x-show="!slideContent[selectedId]?.fileUrl"
            class="flex min-h-[120px] cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-[#24242a] transition hover:border-amber-400/40">
            <svg class="size-7 text-zinc-500" viewBox="0 0 20 20" fill="none">
                <path d="M10 4v8M6 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <span class="text-xs text-zinc-400">Click to upload · MP4, WebM</span>
            <span x-show="uploadProgress.video > 0 && uploadProgress.video < 100" class="text-xs text-amber-400" x-text="`Uploading… ${uploadProgress.video}%`"></span>
            <input type="file" accept="video/mp4,video/webm" class="hidden"
                x-on:change="handleSingleUpload(selectedId, 'fileUrl', $event.target.files[0], 'video')">
        </label>
    </div>

    {{-- YouTube tab --}}
    <div x-show="(slideContent[selectedId]?.sourceTab ?? 'file') === 'yt'" class="flex flex-col gap-2">
        <input
            type="url"
            placeholder="https://www.youtube.com/watch?v=..."
            class="h-10 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
            :value="slideContent[selectedId]?.ytUrl ?? ''"
            x-on:input.debounce.400ms="updateContent(selectedId, 'ytUrl', $event.target.value); $nextTick(() => render())"
        >
        <p class="text-xs text-zinc-600">Paste any YouTube URL — watch, share, or embed link.</p>
    </div>
</div>
