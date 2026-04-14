{{-- QR Code component input panel --}}
<div x-show="getSelectedLeafComponent() === 'qr'" class="flex flex-col gap-4">
    <p class="text-xs font-medium text-zinc-400">QR Code</p>

    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">URL</span>
        <input type="url"
            placeholder="https://example.com"
            class="h-10 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
            :value="slideContent[selectedId]?.url ?? ''"
            x-on:input.debounce.300ms="updateContent(selectedId, 'url', $event.target.value); $nextTick(() => render())"
        >
    </div>

    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">Size in canvas</span>
        <div class="flex gap-2">
            <template x-for="sz in ['S','M','L']" :key="sz">
                <button type="button"
                    :class="(slideContent[selectedId]?.size ?? 'M') === sz
                        ? 'border-amber-400/60 bg-amber-500/10 text-amber-300'
                        : 'border-white/10 text-zinc-500 hover:border-white/20 hover:text-zinc-300'"
                    class="flex-1 rounded-xl border py-2 text-sm font-medium transition"
                    x-on:click="updateContent(selectedId, 'size', sz); $nextTick(() => render())"
                    x-text="sz"
                ></button>
            </template>
        </div>
    </div>

    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">Label below QR</span>
        <input type="text"
            placeholder="Scan me!"
            class="h-10 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
            :value="slideContent[selectedId]?.label ?? ''"
            x-on:input.debounce.300ms="updateContent(selectedId, 'label', $event.target.value); $nextTick(() => render())"
        >
    </div>
</div>
