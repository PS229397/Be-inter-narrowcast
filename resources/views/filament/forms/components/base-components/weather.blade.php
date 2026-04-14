{{-- Weather component input panel --}}
<div x-show="getSelectedLeafComponent() === 'weather'" class="flex flex-col gap-4">
    <p class="text-xs font-medium text-zinc-400">Weather</p>

    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">Location</span>
        <div class="relative">
            <input
                type="text"
                placeholder="Amsterdam, Netherlands"
                class="h-10 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 pr-10 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
                :value="slideContent[selectedId]?.location ?? ''"
                x-on:input.debounce.400ms="updateContent(selectedId, 'location', $event.target.value); $nextTick(() => render())"
            >
            <svg class="pointer-events-none absolute right-3 top-1/2 size-4 -translate-y-1/2 text-zinc-500" viewBox="0 0 20 20" fill="none">
                <path d="M10 2a6 6 0 016 6c0 4-6 10-6 10S4 12 4 8a6 6 0 016-6z" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="10" cy="8" r="2" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        </div>
    </div>

    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">Unit</span>
        <div class="flex gap-2">
            <button type="button"
                :class="(slideContent[selectedId]?.unit ?? 'C') === 'C'
                    ? 'border-amber-400/60 bg-amber-500/10 text-amber-300'
                    : 'border-white/10 text-zinc-500 hover:border-white/20'"
                class="flex-1 rounded-xl border py-2 text-sm font-medium transition"
                x-on:click="updateContent(selectedId, 'unit', 'C'); $nextTick(() => render())"
            >°C — Celsius</button>
            <button type="button"
                :class="(slideContent[selectedId]?.unit ?? 'C') === 'F'
                    ? 'border-amber-400/60 bg-amber-500/10 text-amber-300'
                    : 'border-white/10 text-zinc-500 hover:border-white/20'"
                class="flex-1 rounded-xl border py-2 text-sm font-medium transition"
                x-on:click="updateContent(selectedId, 'unit', 'F'); $nextTick(() => render())"
            >°F — Fahrenheit</button>
        </div>
    </div>

    <p class="rounded-xl border border-white/8 bg-[#24242a] px-4 py-3 text-xs text-zinc-500">
        Live weather data requires an API key (OpenWeatherMap). The canvas shows a styled preview with the configured location.
    </p>
</div>
