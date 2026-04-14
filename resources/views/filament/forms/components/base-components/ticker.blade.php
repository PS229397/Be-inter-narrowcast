{{-- Ticker component input panel --}}
<div x-show="getSelectedLeafComponent() === 'ticker'" class="flex flex-col gap-3">
    <p class="text-xs font-medium text-zinc-400">Scrolling ticker</p>

    <textarea
        placeholder="Breaking news · Type your message here…"
        class="w-full resize-none rounded-xl border border-white/10 bg-[#24242a] px-4 py-3 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
        style="min-height:80px;"
        :value="slideContent[selectedId]?.text ?? ''"
        x-on:input="updateContent(selectedId, 'text', $event.target.value); $nextTick(() => render())"
        x-effect="if (getSelectedLeafComponent() === 'ticker') { const v = slideContent[selectedId]?.text ?? ''; if ($el.value !== v) $el.value = v; }"
    ></textarea>

    {{-- Font size --}}
    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">Font size</span>
        <div class="flex gap-2">
            <template x-for="sz in ['sm','md','lg','xl','2xl']" :key="sz">
                <button type="button"
                    :class="(slideContent[selectedId]?.fontSize ?? 'md') === sz
                        ? 'border-amber-400/60 bg-amber-500/10 text-amber-300'
                        : 'border-white/10 text-zinc-500 hover:border-white/20 hover:text-zinc-300'"
                    class="flex-1 rounded-lg border py-1.5 text-xs font-medium transition"
                    x-on:click="updateContent(selectedId, 'fontSize', sz); $nextTick(() => render())"
                    x-text="sz"
                ></button>
            </template>
        </div>
    </div>

    {{-- Speed --}}
    <div class="flex flex-col gap-1.5">
        <div class="flex items-center justify-between">
            <span class="text-xs font-medium text-zinc-400">Speed</span>
            <span class="text-xs text-amber-400" x-text="['Slow','','','','','Medium','','','','Fast'][+(slideContent[selectedId]?.speed ?? 5) - 1] || (slideContent[selectedId]?.speed ?? 5)"></span>
        </div>
        <input type="range" min="1" max="10" step="1"
            :value="slideContent[selectedId]?.speed ?? 5"
            x-on:input="updateContent(selectedId, 'speed', +$event.target.value); $nextTick(() => render())"
            class="w-full accent-amber-400">
    </div>
</div>
