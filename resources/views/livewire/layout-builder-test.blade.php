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

<div class="mx-auto w-full max-w-[1440px] px-4 py-8 lg:py-16 xl:px-0">
    <div
        wire:key="layout-builder-test-form-{{ $layoutId ?? 'new' }}-{{ $orientation }}"
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
