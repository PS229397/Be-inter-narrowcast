<div>
<style>
    :root {
        --layout-bg:           #0b0b0f;
        --layout-panel:        #1c1c21;
        --layout-panel-alt:    #24242a;
        --layout-panel-deep:   #111114;
        --layout-border:       rgba(255,255,255,0.08);
        --layout-border-strong:rgba(255,255,255,0.12);
        --layout-text:         #ffffff;
        --layout-text-soft:    #e4e4e7;
        --layout-text-muted:   #a1a1aa;
        --layout-text-dim:     #71717a;
        --layout-accent:       #f59e0b;
        --layout-accent-hover: #ffb224;
        --layout-accent-ink:   #141414;
    }

    [data-view-toggle][data-active="true"] {
        background: var(--layout-accent) !important;
        border-color: var(--layout-accent) !important;
        color: var(--layout-accent-ink) !important;
    }
    [data-view-toggle][data-active="true"]:hover {
        background: var(--layout-accent-hover) !important;
        border-color: var(--layout-accent-hover) !important;
    }
    [data-view-toggle][data-active="false"] {
        background: var(--layout-panel-alt) !important;
        border-color: rgba(255,255,255,0.12) !important;
        color: var(--layout-text-muted) !important;
    }
    [data-view-toggle][data-active="false"]:hover {
        border-color: rgba(255,255,255,0.25) !important;
        color: var(--layout-text-soft) !important;
    }
</style>

<div class="mx-auto w-full max-w-[1440px] px-4 py-10 xl:px-0">

    {{-- ─── Header bar ─────────────────────────────────────────────────── --}}
    <div class="mb-6 grid grid-cols-1 gap-4 rounded-2xl border border-white/8 bg-[#1c1c21] p-5 shadow-xl sm:grid-cols-[1fr_1fr_auto]">

        {{-- Title --}}
        <label class="grid gap-1.5">
            <span class="text-xs font-medium text-zinc-400">Title</span>
            <input
                type="text"
                wire:model="title"
                placeholder="Untitled layout"
                class="h-10 rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
            >
        </label>

        {{-- Orientation --}}
        <label class="grid gap-1.5">
            <span class="text-xs font-medium text-zinc-400">Orientation</span>
            <div class="relative">
                <select
                    wire:model.live="orientation"
                    @if($layoutId) disabled @endif
                    class="h-10 w-full appearance-none rounded-xl border border-white/10 bg-[#24242a] px-4 pr-10 text-sm text-white outline-none transition focus:border-amber-400/60 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <option value="landscape">Landscape (16:9)</option>
                    <option value="portrait">Portrait (9:16)</option>
                </select>
                <svg class="pointer-events-none absolute right-3 top-1/2 size-4 -translate-y-1/2 text-zinc-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                </svg>
            </div>
            @if($layoutId)
                <span class="text-xs text-zinc-600">Locked after creation.</span>
            @endif
        </label>

        {{-- Actions --}}
        <div class="flex items-end gap-2">
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                type="button"
                class="h-10 rounded-xl border border-amber-400/50 bg-amber-500/10 px-5 text-sm font-medium text-amber-300 transition hover:border-amber-300 hover:bg-amber-500/20 disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="save">
                    {{ $layoutId ? 'Save' : 'Create' }}
                </span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>

            @if($layoutId)
                <a
                    href="/test"
                    class="grid h-10 w-10 place-items-center rounded-xl border border-white/10 bg-[#24242a] text-zinc-400 transition hover:border-white/20 hover:text-white"
                    title="New layout"
                >
                    <svg class="size-4" viewBox="0 0 20 20" fill="none">
                        <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>

    {{-- ─── Layout Builder field ───────────────────────────────────────── --}}
    <div
        wire:key="layout-builder-test-form-{{ $layoutId ?? 'new' }}-{{ $orientation }}"
        class="rounded-2xl border border-white/8 bg-[#1c1c21] p-1 shadow-xl"
    >
        {{ $this->form }}
    </div>

    @if($layoutId)
        <p class="mt-3 text-center text-xs text-zinc-600">
            Layout #{{ $layoutId }} · <a href="/test?layout={{ $layoutId }}" class="text-zinc-500 underline underline-offset-2 hover:text-zinc-400">Reload</a>
        </p>
    @endif
</div>
</div>
