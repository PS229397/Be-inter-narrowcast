{{-- Countdown component input panel --}}
<div x-show="getSelectedLeafComponent() === 'countdown'" class="flex flex-col gap-4">
    <p class="text-xs font-medium text-zinc-400">Countdown</p>

    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">Target date</span>
        <input type="date"
            class="h-10 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition focus:border-amber-400/60"
            :value="slideContent[selectedId]?.targetDate ?? ''"
            x-on:change="updateContent(selectedId, 'targetDate', $event.target.value); $nextTick(() => render())"
        >
    </div>

    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">Target time</span>
        <input type="time"
            class="h-10 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition focus:border-amber-400/60"
            :value="slideContent[selectedId]?.targetTime ?? '00:00'"
            x-on:change="updateContent(selectedId, 'targetTime', $event.target.value); $nextTick(() => render())"
        >
    </div>

    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">Label</span>
        <input type="text"
            placeholder="Event starts in…"
            class="h-10 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
            :value="slideContent[selectedId]?.label ?? ''"
            x-on:input.debounce.300ms="updateContent(selectedId, 'label', $event.target.value); $nextTick(() => render())"
        >
    </div>
</div>
