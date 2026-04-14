{{-- Clock component input panel --}}
<div x-show="getSelectedLeafComponent() === 'clock'" class="flex flex-col gap-4">
    <p class="text-xs font-medium text-zinc-400">Clock</p>

    {{-- Style toggle --}}
    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">Style</span>
        <div class="flex rounded-xl border border-white/10 bg-[#24242a] p-1">
            <button type="button"
                x-on:click="updateContent(selectedId, 'style', 'digital'); $nextTick(() => render())"
                :class="(slideContent[selectedId]?.style ?? 'digital') === 'digital'
                    ? 'bg-[#1c1c21] text-white shadow-sm'
                    : 'text-zinc-500 hover:text-zinc-300'"
                class="flex-1 rounded-lg py-1.5 text-xs font-medium transition">
                Digital
            </button>
            <button type="button"
                x-on:click="updateContent(selectedId, 'style', 'analog'); $nextTick(() => render())"
                :class="(slideContent[selectedId]?.style ?? 'digital') === 'analog'
                    ? 'bg-[#1c1c21] text-white shadow-sm'
                    : 'text-zinc-500 hover:text-zinc-300'"
                class="flex-1 rounded-lg py-1.5 text-xs font-medium transition">
                Analog
            </button>
        </div>
    </div>

    {{-- Timezone --}}
    <div class="flex flex-col gap-1.5">
        <span class="text-xs font-medium text-zinc-400">Time zone</span>
        <select
            class="h-10 w-full appearance-none rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition focus:border-amber-400/60"
            :value="slideContent[selectedId]?.timezone ?? 'Europe/Amsterdam'"
            x-on:change="updateContent(selectedId, 'timezone', $event.target.value); $nextTick(() => render())"
        >
            <option value="Europe/Amsterdam">Europe/Amsterdam</option>
            <option value="Europe/London">Europe/London</option>
            <option value="Europe/Paris">Europe/Paris</option>
            <option value="Europe/Berlin">Europe/Berlin</option>
            <option value="America/New_York">America/New_York</option>
            <option value="America/Chicago">America/Chicago</option>
            <option value="America/Los_Angeles">America/Los_Angeles</option>
            <option value="Asia/Tokyo">Asia/Tokyo</option>
            <option value="Asia/Singapore">Asia/Singapore</option>
            <option value="Australia/Sydney">Australia/Sydney</option>
            <option value="UTC">UTC</option>
        </select>
    </div>

    {{-- Format (digital only) --}}
    <div class="flex flex-col gap-1.5" x-show="(slideContent[selectedId]?.style ?? 'digital') === 'digital'">
        <span class="text-xs font-medium text-zinc-400">Format</span>
        <select
            class="h-10 w-full appearance-none rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition focus:border-amber-400/60"
            :value="slideContent[selectedId]?.format ?? 'HH:mm:ss'"
            x-on:change="updateContent(selectedId, 'format', $event.target.value); $nextTick(() => render())"
        >
            <option value="HH:mm:ss">HH:mm:ss (24h)</option>
            <option value="HH:mm">HH:mm (24h)</option>
            <option value="hh:mm a">hh:mm a (12h)</option>
            <option value="hh:mm:ss a">hh:mm:ss a (12h)</option>
        </select>
    </div>
</div>
