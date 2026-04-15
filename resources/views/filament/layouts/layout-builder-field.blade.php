<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $statePath            = $getStatePath();
        $orientation          = $getOrientation() ?? 'landscape';
        $titleStatePath       = $getTitleStatePath();
        $orientationStatePath = $getOrientationStatePath();
        $customersStatePath   = $getCustomersStatePath();
        $submitAction         = $getSubmitAction();
        $submitFormId         = $getSubmitFormId();
        $createUrl            = $getCreateUrl();
        $cancelUrl            = $getCancelUrl();
        $isEditing            = $isEditing();
        $isPortrait           = $orientation === 'portrait';
        $isStandalone         = $isStandalone();
        $customerOptions      = $getCustomerOptions();
        $customerOptionsJs    = collect($customerOptions)->mapWithKeys(fn ($name, $id) => [(string) $id => $name])->all();
        $customComponents     = $getCustomComponents();
        $customComponentsKey  = md5(json_encode($customComponents));
        $canvasRatio          = $isPortrait ? '540 / 960' : '960 / 540';
        $canvasMaxW           = $isPortrait ? '360px' : '100%';
        $canvasMaxH           = $isPortrait ? '640px' : '520px';
        $standaloneCanvasW    = $isPortrait ? '540px' : '960px';
        $standaloneCanvasH    = $isPortrait ? '960px' : '540px';
        // Stage is always fixed at exact pixel dimensions, centered inside the 1000×1000 section.
        $standaloneStageStyleX = "orientation === 'portrait' ? 'width:540px;height:960px;' : 'width:960px;height:540px;'";
        $builderExtraAttrs    = $getExtraAttributeBag();
    @endphp

    @if ($isStandalone)
        <div
            wire:key="layout-builder-{{ md5($statePath) }}-{{ $orientation }}-{{ $customComponentsKey }}"
            x-data="layoutBuilder({
                state:       $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false) }},
                orientation: @js($orientation),
                customComponents: @js($customComponents),
                standalone:  true
            })"
            x-init="init()"
            x-on:layout-builder-orientation.window="orientation = $event.detail.value; $nextTick(() => render())"
            class="mx-auto flex flex-row items-stretch gap-5"
            style="height: 1000px;"
            {{ $builderExtraAttrs }}
        >
            @include('filament.layouts.partials.layout-builder-canvas', [
                'sectionClass' => 'relative shrink-0 overflow-hidden rounded-xl border border-white/8 bg-[#1c1c21] shadow-xl',
                'sectionStyle' => 'width:1000px;height:1000px;',
                'innerClass' => 'grid h-full w-full place-items-center',
                'stageStyle' => $isPortrait ? 'width:540px;height:960px;' : 'width:960px;height:540px;',
                'stageStyleX' => $standaloneStageStyleX,
                'stageClass' => 'relative isolate overflow-hidden rounded-xl border border-dashed border-amber-400/20 bg-[#111114] shadow-2xl',
            ])

            <div class="flex h-full w-[380px] shrink-0 flex-col gap-5">
                <div x-data="{}" class="shrink-0 rounded-xl border border-white/8 bg-[#1c1c21] p-4 shadow-xl sm:p-5">
                    <div class="grid gap-5">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="grid gap-2">
                                <span class="text-sm font-medium text-zinc-200">Title</span>
                                <input
                                    type="text"
                                    wire:model.live="{{ $titleStatePath }}"
                                    placeholder="Untitled layout"
                                    class="h-11 rounded-xl border border-white/10 bg-[#111114] px-4 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
                                >
                            </label>

                            <label class="grid gap-2">
                                <span class="text-sm font-medium text-zinc-200">Orientation</span>
                                <div class="relative">
                                    <select
                                        wire:model.live="{{ $orientationStatePath }}"
                                        x-on:change="$dispatch('layout-builder-orientation', { value: $event.target.value })"
                                        @disabled($isEditing)
                                        class="h-11 w-full appearance-none rounded-xl border border-white/10 bg-[#111114] px-4 pr-11 text-sm text-white outline-none transition focus:border-amber-400/60 disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <option value="landscape">Landscape</option>
                                        <option value="portrait">Portrait</option>
                                    </select>
                                    <svg class="pointer-events-none absolute right-4 top-1/2 size-4 -translate-y-1/2 text-zinc-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </label>
                        </div>

                        <div class="grid items-end gap-4 sm:grid-cols-2">
                            <div
                                x-data="{
                                    open: false,
                                    selectedCustomers: $wire.{{ $applyStateBindingModifiers("\$entangle('{$customersStatePath}')", isOptimisticallyLive: false) }},
                                    ids() {
                                        return (Array.isArray(this.selectedCustomers) ? this.selectedCustomers : []).map((id) => String(id));
                                    },
                                    summary() {
                                        const ids = this.ids();
                                        const options = @js($customerOptionsJs);

                                        if (! ids.length) {
                                            return 'All customers';
                                        }

                                        if (ids.length === 1) {
                                            return options[ids[0]] ?? '1 customer';
                                        }

                                        return `${ids.length} customers`;
                                    },
                                    toggle(id) {
                                        const stringId = String(id);
                                        const ids = this.ids();
                                        const next = ids.includes(stringId)
                                            ? ids.filter((value) => value !== stringId)
                                            : [...ids, stringId];

                                        this.selectedCustomers = next.map((value) => Number(value));
                                    },
                                    clear() {
                                        this.selectedCustomers = [];
                                    },
                                }"
                                x-on:click.outside="open = false"
                                class="relative grid gap-2"
                            >
                                <span class="text-sm font-medium text-zinc-200">Customer</span>
                                <button
                                    type="button"
                                    x-on:click="open = ! open"
                                    class="flex h-11 w-full items-center justify-between rounded-xl border border-white/10 bg-[#111114] px-4 text-sm text-white outline-none transition hover:border-white/20"
                                >
                                    <span class="truncate" x-text="summary()"></span>
                                    <svg class="size-4 text-zinc-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div
                                    x-cloak
                                    x-show="open"
                                    class="absolute left-0 right-0 top-full z-30 mt-2 rounded-xl border border-white/10 bg-[#111114] p-2 shadow-2xl"
                                >
                                    <button
                                        type="button"
                                        x-on:click="clear(); open = false"
                                        class="mb-2 flex w-full items-center rounded-lg px-3 py-2 text-left text-sm text-zinc-300 transition hover:bg-white/5 hover:text-white"
                                    >
                                        All customers
                                    </button>

                                    <div class="max-h-48 space-y-1 overflow-y-auto">
                                        @foreach ($customerOptions as $id => $name)
                                            <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm text-zinc-300 transition hover:bg-white/5 hover:text-white">
                                                <input
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-white/15 bg-transparent text-amber-400 focus:ring-amber-400/40"
                                                    x-bind:checked="ids().includes(@js((string) $id))"
                                                    x-on:change="toggle(@js((string) $id))"
                                                >
                                                <span class="truncate">{{ $name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                @if (filled($cancelUrl))
                                    <a
                                        href="{{ $cancelUrl }}"
                                        class="grid h-11 min-w-[92px] place-items-center rounded-xl border border-white/10 bg-[#111114] px-4 text-sm font-medium text-zinc-300 transition hover:border-white/20 hover:text-white"
                                    >
                                        Cancel
                                    </a>
                                @endif

                                <button
                                    type="submit"
                                    form="{{ $submitFormId ?? 'form' }}"
                                    wire:loading.attr="disabled"
                                    wire:target="{{ $submitAction }}"
                                    class="h-11 flex-1 rounded-xl border border-amber-400/40 bg-amber-500/10 text-sm font-medium text-amber-300 transition hover:border-amber-300 hover:bg-amber-500/20 disabled:opacity-50"
                                >
                                    <span wire:loading.remove wire:target="{{ $submitAction }}">{{ $isEditing ? 'Save' : 'Create' }}</span>
                                    <span wire:loading wire:target="{{ $submitAction }}">{{ $isEditing ? 'Saving…' : 'Creating…' }}</span>
                                </button>

                                @if ($isEditing && filled($createUrl))
                                    <a
                                        href="{{ $createUrl }}"
                                        class="grid h-11 w-11 shrink-0 place-items-center rounded-xl border border-white/10 bg-[#111114] text-zinc-400 transition hover:border-white/20 hover:text-white"
                                        title="New layout"
                                    >
                                        <svg class="size-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                            <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <p class="text-xs text-zinc-500">
                            Leave customer empty to make the layout available to all customers.
                        </p>
                    </div>
                </div>

                @include('filament.layouts.partials.layout-builder-panel', [
                    'asideClass' => 'min-h-0 rounded-xl border border-white/8 bg-[#1c1c21] shadow-xl flex flex-1 flex-col',
                ])
            </div>
        </div>
    @else
        <div
            wire:key="layout-builder-{{ md5($statePath) }}-{{ $orientation }}-{{ $customComponentsKey }}"
            wire:ignore
            x-data="layoutBuilder({
                state:       $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false) }},
                orientation: @js($orientation),
                customComponents: @js($customComponents),
                standalone:  false
            })"
            x-init="init()"
            {{ $builderExtraAttrs }}
        >
            <div class="grid gap-5" style="grid-template-columns: 1fr 280px;">
                @include('filament.layouts.partials.layout-builder-canvas', [
                    'sectionClass' => 'relative overflow-hidden rounded-xl border border-white/8 bg-[#1c1c21] shadow-xl',
                    'innerClass' => 'flex min-h-[420px] items-center justify-center p-4',
                    'stageStyle' => "aspect-ratio: {$canvasRatio}; max-width: {$canvasMaxW}; max-height: {$canvasMaxH};",
                    'stageClass' => 'relative isolate w-full overflow-hidden rounded-xl border border-dashed border-amber-400/20 bg-[#111114] shadow-2xl',
                ])

                @include('filament.layouts.partials.layout-builder-panel', [
                    'asideClass' => 'flex flex-col rounded-xl border border-white/8 bg-[#1c1c21] shadow-xl',
                ])
            </div>
        </div>
    @endif

    @script
    <script>
    (() => {
        const register = () => {
            if (! window.Alpine || window.__layoutBuilderRegistered) {
                return;
            }

            window.__layoutBuilderRegistered = true;

            Alpine.data('layoutBuilder', (config) => ({
        state:       config.state,
        orientation: config.orientation ?? 'landscape',
        customComponents: config.customComponents ?? [],

        realSizes: {
            landscape: { width: 1920, height: 1080 },
            portrait:  { width: 1080, height: 1920 },
        },

        componentDefs: {
            text:      '<path d="M5 5h10M10 5v10M7 15h6" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>',
            image:     '<rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1"/><circle cx="7.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1"/><path d="M2 13.5l4-4 3 3 2.5-2.5 4.5 4.5" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>',
            video:     '<rect x="2" y="5" width="12" height="10" rx="2" stroke="currentColor" stroke-width="1"/><path d="M14 8.5l4-2v5l-4-2V8.5z" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>',
            carousel:  '<rect x="1" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1" opacity="0.4"/><rect x="6" y="3" width="8" height="11" rx="1.5" stroke="currentColor" stroke-width="1"/><rect x="15" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1" opacity="0.4"/><circle cx="8.5" cy="17.5" r="0.75" fill="currentColor"/><circle cx="10" cy="17.5" r="0.75" fill="currentColor"/><circle cx="11.5" cy="17.5" r="0.75" fill="currentColor"/>',
            ticker:    '<rect x="2" y="7" width="16" height="6" rx="1.5" stroke="currentColor" stroke-width="1"/><path d="M5 10h5" stroke="currentColor" stroke-width="1" stroke-linecap="round"/><path d="M13 8.5l2.5 1.5-2.5 1.5" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>',
            clock:     '<circle cx="10" cy="10" r="7.5" stroke="currentColor" stroke-width="1"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>',
            weather:   '<circle cx="10" cy="8.5" r="3" stroke="currentColor" stroke-width="1"/><path d="M10 3v1M10 14v1M3.5 8.5h1M15.5 8.5h1M5.6 4.6l.7.7M13.7 4.6l-.7.7" stroke="currentColor" stroke-width="1" stroke-linecap="round"/><path d="M5 16a3 3 0 010-6h.5a4 4 0 017 0H13a3 3 0 010 6H5z" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>',
            countdown: '<circle cx="10" cy="11" r="7" stroke="currentColor" stroke-width="1"/><path d="M10 8v3l-2.5 2" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 3h4M10 1v2" stroke="currentColor" stroke-width="1" stroke-linecap="round"/>',
            qr:        '<rect x="3" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1"/><rect x="12" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1"/><rect x="3" y="12" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1"/><rect x="4.5" y="4.5" width="2" height="2" fill="currentColor"/><rect x="13.5" y="4.5" width="2" height="2" fill="currentColor"/><rect x="4.5" y="13.5" width="2" height="2" fill="currentColor"/><path d="M12 12h2v2h-2zM14 14h2v2h-2zM12 16h2M16 12v2" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>',
            custom:    '<path d="M4 8.5A2.5 2.5 0 0 1 6.5 6H8V4.5A1.5 1.5 0 0 1 9.5 3h1A1.5 1.5 0 0 1 12 4.5V6h1.5A2.5 2.5 0 0 1 16 8.5v1A2.5 2.5 0 0 1 13.5 12H12v1.5A1.5 1.5 0 0 1 10.5 15h-1A1.5 1.5 0 0 1 8 13.5V12H6.5A2.5 2.5 0 0 1 4 9.5v-1Z" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/><path d="M8 9h4M10 7v4" stroke="currentColor" stroke-width="1" stroke-linecap="round"/>',
        },

        canvasCornerRadius: '0.75rem',
        grid:        null,
        nodeCounter: 0,
        selectedId:  null,
        isDragging:  false,

        // ─── Lifecycle ──────────────────────────────────────────────────────

        init() {
            this._initGrid();
            this.$watch('state', (val) => {
                const normalized = this._normalizeGrid(val);

                if (JSON.stringify(normalized) !== JSON.stringify(this.grid)) {
                    this.grid        = normalized;
                    this.nodeCounter = this._maxNodeId(this.grid);
                    this.$nextTick(() => this.render());
                }
            });
            this.$nextTick(() => this.render());
        },

        // ─── Grid helpers ────────────────────────────────────────────────────

        _initGrid() {
            this.grid = this._normalizeGrid(this.state);
            this.nodeCounter = this._maxNodeId(this.grid);
        },

        _emptyGrid() {
            return { id: 'root', direction: null, split: 50, children: [], component: null };
        },

        _normalizeGrid(raw) {
            if (!raw || (Array.isArray(raw) && raw.length === 0)) {
                return this._emptyGrid();
            }

            try {
                const parsed = typeof raw === 'string' ? JSON.parse(raw) : JSON.parse(JSON.stringify(raw));

                if (!parsed || Array.isArray(parsed) || typeof parsed !== 'object') {
                    return this._emptyGrid();
                }

                if (!Array.isArray(parsed.children)) {
                    return this._emptyGrid();
                }

                return this._normalizeNode(parsed, 'root');
            } catch (_) {
                return this._emptyGrid();
            }
        },

        _normalizeNode(node, fallbackId = null) {
            const normalized = {
                id: typeof node.id === 'string' && node.id !== '' ? node.id : (fallbackId ?? this._makeNode().id),
                direction: node.direction === 'h' || node.direction === 'v' ? node.direction : null,
                split: typeof node.split === 'number' ? Math.max(5, Math.min(95, node.split)) : 50,
                children: [],
                component: typeof node.component === 'string' && node.component !== '' ? node.component : null,
            };

            if (Array.isArray(node.children) && node.children.length > 0) {
                normalized.children = node.children
                    .slice(0, 2)
                    .map((child) => this._normalizeNode(child));
            }

            if (normalized.children.length !== 2) {
                normalized.children = [];
                normalized.direction = null;
            }

            return normalized;
        },

        _maxNodeId(node) {
            let max = 0;
            const walk = (n) => {
                const m = parseInt((n.id ?? '').replace(/\D/g, '')) || 0;
                if (m > max) max = m;
                (n.children ?? []).forEach(walk);
            };
            walk(node);
            return max;
        },

        _makeNode() {
            return { id: `n${++this.nodeCounter}`, direction: null, split: 50, children: [], component: null };
        },

        _save() {
            this.state = JSON.parse(JSON.stringify(this.grid));
        },

        findNode(node, id) {
            if (node.id === id) return node;
            for (const c of (node.children ?? [])) {
                const hit = this.findNode(c, id);
                if (hit) return hit;
            }
            return null;
        },

        findParent(node, id) {
            for (const c of (node.children ?? [])) {
                if (c.id === id) return node;
                const hit = this.findParent(c, id);
                if (hit) return hit;
            }
            return null;
        },

        // ─── Actions ─────────────────────────────────────────────────────────

        slice(nodeId, direction) {
            const node = this.findNode(this.grid, nodeId);
            if (!node || (node.children ?? []).length > 0) return;
            const inherited   = node.component;
            node.direction    = direction;
            node.split        = 50;
            node.component    = null;
            node.children     = [this._makeNode(), this._makeNode()];
            node.children[0].component = inherited;
            this.selectedId   = node.children[0].id;
            this._save();
            this.render();
        },

        deleteNode(nodeId) {
            if (nodeId === 'root') return;
            const parent = this.findParent(this.grid, nodeId);
            if (!parent) return;
            parent.children = parent.children.filter(c => c.id !== nodeId);
            if (parent.children.length === 1) {
                const survivor    = parent.children[0];
                parent.direction  = survivor.direction;
                parent.split      = survivor.split;
                parent.component  = survivor.component;
                parent.children   = survivor.children;
            }
            this.selectedId = null;
            this._save();
            this.render();
        },

        clearCanvas() {
            this.nodeCounter = 0;
            this.grid        = { id: 'root', direction: null, split: 50, children: [], component: null };
            this.selectedId  = null;
            this._save();
            this.render();
        },

        assignComponent(type) {
            if (!this.selectedId) return;
            const node = this.findNode(this.grid, this.selectedId);
            if (!node || (node.children ?? []).length > 0) return;
            node.component = node.component === type ? null : type;
            this._save();
            this.render();
        },

        isCustomComponent(type) {
            return typeof type === 'string' && type.startsWith('custom:');
        },

        getCustomComponent(type) {
            if (!this.isCustomComponent(type)) {
                return null;
            }

            return this.customComponents.find((component) => component.key === type) ?? null;
        },

        // ─── Rendering ───────────────────────────────────────────────────────

        fmtPill(wPct, hPct) {
            const real = this.realSizes[this.orientation] ?? this.realSizes.landscape;
            return `${Math.round(real.width * wPct / 100)}×${Math.round(real.height * hPct / 100)}px`;
        },

        leafOrder(node, map = new Map(), counter = { n: 0 }) {
            if ((node.children ?? []).length === 0) {
                map.set(node.id, ++counter.n);
            } else {
                for (const c of node.children) this.leafOrder(c, map, counter);
            }
            return map;
        },

        applyCornerRadius(el, corners) {
            el.style.borderTopLeftRadius     = corners.topLeft     ? this.canvasCornerRadius : '0';
            el.style.borderTopRightRadius    = corners.topRight    ? this.canvasCornerRadius : '0';
            el.style.borderBottomRightRadius = corners.bottomRight ? this.canvasCornerRadius : '0';
            el.style.borderBottomLeftRadius  = corners.bottomLeft  ? this.canvasCornerRadius : '0';
        },

        render() {
            const container = this.$refs.gridContainer;
            if (!container || !this.grid) return;

            container.innerHTML = '';
            const rootEl = this.buildEl(this.grid, 100, 100, this.leafOrder(this.grid));
            rootEl.style.position = 'absolute';
            rootEl.style.inset    = '0';
            container.appendChild(rootEl);

            const selectedNode = this.selectedId ? this.findNode(this.grid, this.selectedId) : null;
            const hasSelection = !!this.selectedId;
            const overlay      = this.$refs.canvasOverlay;

            if (overlay) {
                overlay.classList.toggle('hidden', !hasSelection);

                if (hasSelection) {
                    const isLeaf = selectedNode && (selectedNode.children ?? []).length === 0;
                    this.$refs.btnSliceH?.classList.toggle('hidden', !isLeaf);
                    this.$refs.btnSliceV?.classList.toggle('hidden', !isLeaf);
                    this.$refs.btnDelete?.classList.toggle('hidden', this.selectedId === 'root');
                }
            }

            // Highlight active component picker button (admin only)
            this.$el.querySelectorAll('[data-component]').forEach(btn => {
                const active = selectedNode?.component === btn.dataset.component;
                btn.classList.toggle('border-amber-400/60', active);
                btn.classList.toggle('text-amber-300',      active);
                btn.classList.toggle('border-white/10',    !active);
                btn.classList.toggle('text-zinc-400',      !active);
            });
        },

        // ─── Canvas element builder ──────────────────────────────────────────

        buildEl(
            node,
            wPct    = 100,
            hPct    = 100,
            order   = new Map(),
            corners = { topLeft: true, topRight: true, bottomRight: true, bottomLeft: true },
        ) {
            const el = document.createElement('div');
            el.dataset.nodeId = node.id;
            el.style.cssText  = 'width:100%;height:100%;position:relative;overflow:hidden;box-sizing:border-box;';

            if ((node.children ?? []).length > 0) {
                const isH   = node.direction === 'h';
                const split = node.split ?? 50;

                const child1Corners = isH
                    ? { topLeft: corners.topLeft,  topRight: corners.topRight, bottomRight: false,              bottomLeft: false              }
                    : { topLeft: corners.topLeft,  topRight: false,             bottomRight: false,              bottomLeft: corners.bottomLeft  };
                const child2Corners = isH
                    ? { topLeft: false,             topRight: false,             bottomRight: corners.bottomRight, bottomLeft: corners.bottomLeft  }
                    : { topLeft: false,             topRight: corners.topRight,  bottomRight: corners.bottomRight, bottomLeft: false               };

                el.style.display       = 'flex';
                el.style.flexDirection = isH ? 'column' : 'row';

                const w1 = document.createElement('div');
                w1.style.cssText = isH
                    ? `flex:0 0 ${split}%;min-height:0;position:relative;overflow:hidden;`
                    : `flex:0 0 ${split}%;min-width:0;position:relative;overflow:hidden;`;

                const dashIdle   = 'rgba(245,158,11,0.3)';
                const dashHover  = 'rgba(245,158,11,0.7)';
                const dashActive = 'rgba(245,158,11,1)';
                const isInteractiveDivider = true;
                const dashedBg   = (color, dir) => dir === 'h'
                    ? `repeating-linear-gradient(to right,${color} 0,${color} 4px,transparent 4px,transparent 8px)`
                    : `repeating-linear-gradient(to bottom,${color} 0,${color} 4px,transparent 4px,transparent 8px)`;

                const handle = document.createElement('div');
                handle.style.cssText = isH
                    ? (isInteractiveDivider
                        ? 'box-sizing:content-box;flex:0 0 1px;padding:7px 0;margin:-7px 0;cursor:row-resize;position:relative;z-index:10;display:flex;align-items:center;'
                        : 'flex:0 0 1px;position:relative;z-index:1;display:flex;align-items:center;pointer-events:none;')
                    : (isInteractiveDivider
                        ? 'box-sizing:content-box;flex:0 0 1px;padding:0 7px;margin:0 -7px;cursor:col-resize;position:relative;z-index:10;display:flex;justify-content:center;'
                        : 'flex:0 0 1px;position:relative;z-index:1;display:flex;justify-content:center;pointer-events:none;');

                const line = document.createElement('div');
                line.style.cssText = isH
                    ? 'width:100%;height:1px;transition:background 0.15s;'
                    : 'height:100%;width:1px;transition:background 0.15s;';
                line.style.background = isInteractiveDivider
                    ? dashedBg(dashIdle, node.direction)
                    : 'rgba(245,158,11,0.15)';
                handle.appendChild(line);

                if (isInteractiveDivider) {
                    handle.addEventListener('mouseenter', () => line.style.background = dashedBg(dashHover, node.direction));
                    handle.addEventListener('mouseleave', () => { if (!handle._drag) line.style.background = dashedBg(dashIdle, node.direction); });
                    handle.addEventListener('click', e => e.stopPropagation());

                    handle.addEventListener('mousedown', e => {
                        e.stopPropagation();
                        e.preventDefault();
                        handle._drag    = true;
                        this.isDragging = true;
                        line.style.background = dashedBg(dashActive, node.direction);
                        const rect = el.getBoundingClientRect();
                        const onMove = e => {
                            const raw     = isH
                                ? (e.clientY - rect.top)  / rect.height * 100
                                : (e.clientX - rect.left) / rect.width  * 100;
                            const clamped = Math.max(5, Math.min(95, Math.round(raw / 5) * 5));
                            w1.style.flex = `0 0 ${clamped}%`;
                            node.split    = clamped;
                            const lbl1 = w1.querySelector('[data-pct-label]');
                            const lbl2 = w2.querySelector('[data-pct-label]');
                            if (lbl1) lbl1.textContent = this.fmtPill(isH ? wPct : wPct * clamped / 100,         isH ? hPct * clamped / 100         : hPct);
                            if (lbl2) lbl2.textContent = this.fmtPill(isH ? wPct : wPct * (100 - clamped) / 100, isH ? hPct * (100 - clamped) / 100 : hPct);
                        };
                        const onUp = () => {
                            handle._drag  = false;
                            this.isDragging = false;
                            line.style.background = dashedBg(dashIdle, node.direction);
                            this._save();
                            document.removeEventListener('mousemove', onMove);
                            document.removeEventListener('mouseup',   onUp);
                        };
                        document.addEventListener('mousemove', onMove);
                        document.addEventListener('mouseup',   onUp);
                    });
                }

                const w2 = document.createElement('div');
                w2.style.cssText = isH
                    ? 'flex:1;min-height:0;position:relative;overflow:hidden;'
                    : 'flex:1;min-width:0;position:relative;overflow:hidden;';

                w1.appendChild(this.buildEl(node.children[0], isH ? wPct : wPct * split / 100,         isH ? hPct * split / 100         : hPct, order, child1Corners));
                w2.appendChild(this.buildEl(node.children[1], isH ? wPct : wPct * (100 - split) / 100, isH ? hPct * (100 - split) / 100 : hPct, order, child2Corners));
                el.appendChild(w1);
                el.appendChild(handle);
                el.appendChild(w2);

            } else {
                // ── Leaf node ──
                this.applyCornerRadius(el, corners);
                el.style.backgroundColor = 'rgba(17,17,20,0.55)';
                el.style.cursor          = 'pointer';
                el.style.transition      = 'background-color 0.15s';

                const center = document.createElement('div');
                center.style.cssText = 'position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;z-index:4;user-select:none;';
                if (node.component && this.componentDefs[node.component]) {
                    center.innerHTML = `<svg viewBox="0 0 20 20" fill="none" style="width:56px;height:56px;color:rgba(245,158,11,0.5)">${this.componentDefs[node.component]}</svg>`;
                } else if (this.isCustomComponent(node.component)) {
                    const customComponent = this.getCustomComponent(node.component);
                    const label = customComponent?.title ?? 'Custom';

                    center.style.flexDirection = 'column';
                    center.style.gap = '8px';
                    center.innerHTML = `<svg viewBox="0 0 20 20" fill="none" style="width:56px;height:56px;color:rgba(245,158,11,0.5)">${this.componentDefs.custom}</svg>`;

                    const labelEl = document.createElement('div');
                    labelEl.style.cssText = 'max-width:80%;font-size:12px;font-weight:600;line-height:1.2;color:rgba(245,158,11,0.72);text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;';
                    labelEl.textContent = label;
                    center.appendChild(labelEl);
                } else {
                    center.style.fontSize   = '48px';
                    center.style.fontWeight = '700';
                    center.style.color      = 'rgba(245,158,11,0.35)';
                    center.style.fontFamily = 'monospace';
                    center.textContent = order.get(node.id) ?? '';
                }
                el.appendChild(center);

                const pill = document.createElement('div');
                pill.dataset.pctLabel = '';
                pill.textContent      = this.fmtPill(wPct, hPct);
                pill.style.cssText    = 'position:absolute;bottom:5px;left:6px;font-size:10px;line-height:1;color:rgba(161,161,170,0.7);background:rgba(17,17,20,0.7);border:1px solid rgba(161,161,170,0.12);border-radius:999px;padding:3px 8px;pointer-events:none;z-index:5;user-select:none;font-family:monospace;white-space:nowrap;';
                el.appendChild(pill);

                el.addEventListener('mouseenter', () => {
                    if (!this.isDragging && node.id !== this.selectedId)
                        el.style.backgroundColor = 'rgba(245,158,11,0.10)';
                });
                el.addEventListener('mouseleave', () => {
                    if (!this.isDragging && node.id !== this.selectedId)
                        el.style.backgroundColor = 'rgba(17,17,20,0.55)';
                });
            }

            if (node.id === this.selectedId) {
                el.style.outline       = '2px solid rgba(245,158,11,0.75)';
                el.style.outlineOffset = '-2px';
            }

            el.addEventListener('click', e => {
                e.stopPropagation();
                this.selectedId = node.id;
                this.render();
            });

            return el;
        },

        // ─── Component canvas renderers ──────────────────────────────────────

        _buildComponentEl(el, node) {
            const content = this.slideContent[node.id] ?? {};
            el.style.backgroundColor = '#111114';
            el.style.overflow        = 'hidden';

            switch (node.component) {

                case 'text': {
                    const wrap = document.createElement('div');
                    wrap.style.cssText = 'position:absolute;inset:0;overflow:hidden;padding:10px 12px;font-size:13px;line-height:1.6;color:#e4e4e7;max-width:100%;overflow-wrap:anywhere;word-break:break-word;white-space:normal;';
                    wrap.innerHTML = content.html || content.text || '';
                    this._styleRichTextContent(wrap);
                    el.appendChild(wrap);
                    break;
                }

                case 'image': {
                    if (content.url) {
                        const img = document.createElement('img');
                        img.src            = content.url;
                        img.style.cssText  = 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;';
                        el.appendChild(img);
                    } else {
                        this._placeholder(el, node.component);
                    }
                    break;
                }

                case 'video': {
                    const tab = content.sourceTab ?? 'file';
                    if (tab === 'file' && content.fileUrl) {
                        const vid = document.createElement('video');
                        vid.src              = content.fileUrl;
                        vid.autoplay         = true;
                        vid.muted            = true;
                        vid.loop             = true;
                        vid.playsInline      = true;
                        vid.style.cssText    = 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;';
                        el.appendChild(vid);
                        vid.play().catch(() => {});
                    } else if (tab === 'yt' && content.ytUrl) {
                        const id = this._ytId(content.ytUrl);
                        if (id) {
                            const iframe = document.createElement('iframe');
                            iframe.src              = `https://www.youtube.com/embed/${id}?autoplay=1&mute=1&loop=1&playlist=${id}&controls=0&modestbranding=1`;
                            iframe.allow            = 'autoplay';
                            iframe.style.cssText    = 'position:absolute;inset:0;width:100%;height:100%;border:0;';
                            el.appendChild(iframe);
                        } else {
                            this._placeholder(el, 'video');
                        }
                    } else {
                        this._placeholder(el, node.component);
                    }
                    break;
                }

                case 'carousel': {
                    const images  = content.images ?? [];
                    const dur     = (content.duration ?? 5) * 1000;
                    if (!images.length) { this._placeholder(el, 'carousel'); break; }

                    // Create image layers, crossfade between them
                    const layers = images.map((src, i) => {
                        const img = document.createElement('img');
                        img.src           = src;
                        img.style.cssText = `position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:opacity 0.8s;opacity:${i === 0 ? 1 : 0};`;
                        el.appendChild(img);
                        return img;
                    });

                    let current = 0;
                    const advance = () => {
                        layers[current].style.opacity = '0';
                        current = (current + 1) % layers.length;
                        layers[current].style.opacity = '1';
                    };
                    const handle = setInterval(advance, dur);
                    this.liveHandles.push(handle);
                    break;
                }

                case 'ticker': {
                    const text = content.text || '';
                    if (!text) { this._placeholder(el, 'ticker'); break; }

                    const fontSizeMap = { sm: '11px', md: '14px', lg: '18px', xl: '22px', '2xl': '28px' };
                    const fs    = fontSizeMap[content.fontSize ?? 'md'] ?? '14px';
                    const speed = content.speed ?? 5;

                    const bar = document.createElement('div');
                    bar.style.cssText = 'position:absolute;inset:0;display:flex;align-items:center;overflow:hidden;';

                    const track = document.createElement('div');
                    track.style.cssText = 'width:100%;height:28%;min-height:24px;display:flex;align-items:center;overflow:hidden;background:rgba(0,0,0,0.65);';

                    const inner = document.createElement('div');
                    inner.style.cssText = `display:inline-flex;align-items:center;line-height:1;white-space:nowrap;font-size:${fs};color:#f5f5f5;font-weight:500;will-change:transform;`;
                    inner.textContent   = text + '  •  ' + text + '  •  '; // doubled for seamless loop
                    track.appendChild(inner);
                    bar.appendChild(track);
                    el.appendChild(bar);

                    // Animate after layout
                    requestAnimationFrame(() => {
                        const w      = inner.scrollWidth / 2;
                        const startX = track.clientWidth || bar.clientWidth || el.clientWidth || 0;
                        const endX   = -w;
                        const px_s   = 40 + speed * 20;
                        const dur_ms = ((startX + w) / px_s) * 1000;
                        inner.style.animation = `none`;
                        const keyframes = `@keyframes lbticker_${node.id}{from{transform:translateX(${startX}px)}to{transform:translateX(${endX}px)}}`;
                        let styleEl = document.getElementById(`lb-ticker-style-${node.id}`);
                        if (!styleEl) {
                            styleEl = document.createElement('style');
                            styleEl.id = `lb-ticker-style-${node.id}`;
                            document.head.appendChild(styleEl);
                        }
                        styleEl.textContent = keyframes;
                        inner.style.animation = `lbticker_${node.id} ${dur_ms}ms linear infinite`;
                    });
                    break;
                }

                case 'clock': {
                    const tz     = content.timezone ?? 'Europe/Amsterdam';
                    const style  = content.style    ?? 'digital';
                    const fmt    = content.format   ?? 'HH:mm:ss';

                    if (style === 'analog') {
                        const svgNS  = 'http://www.w3.org/2000/svg';
                        const size   = Math.min(el.offsetWidth || 120, el.offsetHeight || 120) * 0.8;
                        const cx     = '50%', cy = '50%';
                        const r      = 46;

                        const svg = document.createElementNS(svgNS, 'svg');
                        svg.setAttribute('viewBox', '0 0 100 100');
                        svg.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;';

                        // Face
                        const face = document.createElementNS(svgNS, 'circle');
                        face.setAttribute('cx', '50'); face.setAttribute('cy', '50');
                        face.setAttribute('r', String(r));
                        face.setAttribute('fill', 'rgba(17,17,20,0.8)');
                        face.setAttribute('stroke', 'rgba(245,158,11,0.4)');
                        face.setAttribute('stroke-width', '1.5');
                        svg.appendChild(face);

                        // Hour markers
                        for (let i = 0; i < 12; i++) {
                            const ang = (i / 12) * Math.PI * 2 - Math.PI / 2;
                            const x1 = 50 + Math.cos(ang) * 40, y1 = 50 + Math.sin(ang) * 40;
                            const x2 = 50 + Math.cos(ang) * 44, y2 = 50 + Math.sin(ang) * 44;
                            const t  = document.createElementNS(svgNS, 'line');
                            t.setAttribute('x1', x1); t.setAttribute('y1', y1);
                            t.setAttribute('x2', x2); t.setAttribute('y2', y2);
                            t.setAttribute('stroke', 'rgba(245,158,11,0.5)');
                            t.setAttribute('stroke-width', '1.5');
                            svg.appendChild(t);
                        }

                        const mkHand = (w, l, color) => {
                            const hand = document.createElementNS(svgNS, 'line');
                            hand.setAttribute('x1', '50'); hand.setAttribute('y1', '50');
                            hand.setAttribute('stroke', color);
                            hand.setAttribute('stroke-width', w);
                            hand.setAttribute('stroke-linecap', 'round');
                            svg.appendChild(hand);
                            return hand;
                        };

                        const hourHand   = mkHand('3',   '28', 'rgba(245,158,11,0.9)');
                        const minuteHand = mkHand('2',   '38', '#e4e4e7');
                        const secondHand = mkHand('1.2', '42', 'rgba(245,158,11,1)');

                        // Center dot
                        const dot = document.createElementNS(svgNS, 'circle');
                        dot.setAttribute('cx','50'); dot.setAttribute('cy','50'); dot.setAttribute('r','2');
                        dot.setAttribute('fill','rgba(245,158,11,1)');
                        svg.appendChild(dot);

                        el.appendChild(svg);

                        const setHands = () => {
                            const now = new Date(new Date().toLocaleString('en-US', { timeZone: tz }));
                            const h   = now.getHours() % 12 + now.getMinutes() / 60;
                            const m   = now.getMinutes() + now.getSeconds() / 60;
                            const s   = now.getSeconds();
                            const ang = (a, r) => ({ x: 50 + Math.cos((a / 360) * Math.PI * 2 - Math.PI / 2) * r, y: 50 + Math.sin((a / 360) * Math.PI * 2 - Math.PI / 2) * r });
                            const hP  = ang(h * 30, 28);
                            const mP  = ang(m * 6,  38);
                            const sP  = ang(s * 6,  42);
                            hourHand.setAttribute('x2',   String(hP.x)); hourHand.setAttribute('y2',   String(hP.y));
                            minuteHand.setAttribute('x2', String(mP.x)); minuteHand.setAttribute('y2', String(mP.y));
                            secondHand.setAttribute('x2', String(sP.x)); secondHand.setAttribute('y2', String(sP.y));
                        };
                        setHands();
                        this.liveHandles.push(setInterval(setHands, 1000));

                    } else {
                        // Digital
                        const display = document.createElement('div');
                        display.style.cssText = 'position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;';

                        const timeEl = document.createElement('div');
                        timeEl.style.cssText = 'font-variant-numeric:tabular-nums;font-family:monospace;font-size:clamp(16px,5cqi,48px);font-weight:700;color:#f5f5f5;letter-spacing:0.05em;';
                        display.appendChild(timeEl);

                        const tzEl = document.createElement('div');
                        tzEl.style.cssText = 'font-size:10px;color:rgba(245,158,11,0.7);letter-spacing:0.08em;text-transform:uppercase;';
                        tzEl.textContent   = tz.split('/').pop().replace('_', ' ');
                        display.appendChild(tzEl);

                        el.appendChild(display);

                        const pad = n => String(n).padStart(2, '0');
                        const tick = () => {
                            const now = new Date(new Date().toLocaleString('en-US', { timeZone: tz }));
                            let   h   = now.getHours(), m = now.getMinutes(), s = now.getSeconds();
                            const pm  = h >= 12;
                            if (fmt.includes('hh')) h = h % 12 || 12;
                            let   str = `${pad(h)}:${pad(m)}`;
                            if (fmt.includes('ss')) str += `:${pad(s)}`;
                            if (fmt.includes('a'))  str += ` ${pm ? 'PM' : 'AM'}`;
                            timeEl.textContent = str;
                        };
                        tick();
                        this.liveHandles.push(setInterval(tick, 1000));
                    }
                    break;
                }

                case 'weather': {
                    const loc  = content.location || 'Unknown location';
                    const unit = content.unit ?? 'C';
                    const wrap = document.createElement('div');
                    wrap.style.cssText = 'position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:8px;';
                    wrap.innerHTML = `
                        <svg viewBox="0 0 40 40" fill="none" style="width:clamp(24px,4cqi,40px);height:auto;color:rgba(245,158,11,0.7)">
                            <circle cx="20" cy="15" r="6" stroke="currentColor" stroke-width="2"/>
                            <path d="M20 5v2M20 24v2M8 15h2M30 15h2M11.5 8.5l1.5 1.5M27 8.5l-1.5 1.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M8 32a6 6 0 010-12h1a8 8 0 0114 0h2a6 6 0 010 12H8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                        <div style="font-size:clamp(16px,4cqi,28px);font-weight:700;color:#f5f5f5;line-height:1;">--°${unit}</div>
                        <div style="font-size:clamp(9px,1.8cqi,13px);color:rgba(245,158,11,0.8);font-weight:500;text-align:center;max-width:90%;">${loc}</div>
                        <div style="font-size:9px;color:rgba(161,161,170,0.5);letter-spacing:0.05em;">LIVE DATA — API PENDING</div>`;
                    el.appendChild(wrap);
                    break;
                }

                case 'countdown': {
                    const targetDate = content.targetDate ?? '';
                    const targetTime = content.targetTime ?? '00:00';
                    const label      = content.label      ?? '';

                    const wrap = document.createElement('div');
                    wrap.style.cssText = 'position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:8px;';

                    const labelEl = document.createElement('div');
                    labelEl.style.cssText = 'font-size:clamp(8px,1.5cqi,11px);color:rgba(245,158,11,0.7);letter-spacing:0.1em;text-transform:uppercase;font-weight:600;';
                    labelEl.textContent   = label;

                    const timerEl = document.createElement('div');
                    timerEl.style.cssText = 'font-variant-numeric:tabular-nums;font-family:monospace;font-size:clamp(14px,3.5cqi,36px);font-weight:700;color:#f5f5f5;letter-spacing:0.04em;';

                    wrap.appendChild(labelEl);
                    wrap.appendChild(timerEl);
                    el.appendChild(wrap);

                    if (!targetDate) { timerEl.textContent = '00:00:00:00'; break; }

                    const target = new Date(`${targetDate}T${targetTime}:00`);
                    const tick = () => {
                        const diff = target - Date.now();
                        if (diff <= 0) { timerEl.textContent = '00:00:00:00'; return; }
                        const d = Math.floor(diff / 86400000);
                        const h = Math.floor((diff % 86400000) / 3600000);
                        const m = Math.floor((diff % 3600000)  / 60000);
                        const s = Math.floor((diff % 60000)    / 1000);
                        const p = n => String(n).padStart(2, '0');
                        timerEl.textContent = `${p(d)}:${p(h)}:${p(m)}:${p(s)}`;
                    };
                    tick();
                    this.liveHandles.push(setInterval(tick, 1000));
                    break;
                }

                case 'qr': {
                    const url     = content.url   ?? '';
                    const sizeKey = content.size  ?? 'M';
                    const qrLabel = content.label ?? '';
                    const sizeMap = { S: 0.35, M: 0.55, L: 0.75 };
                    const pct     = sizeMap[sizeKey] ?? 0.55;

                    const wrap = document.createElement('div');
                    wrap.style.cssText = 'position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:8px;';

                    if (url && window.QRCode) {
                        const canvas = document.createElement('canvas');
                        const size   = Math.min(el.offsetWidth || 150, el.offsetHeight || 150) * pct;
                        canvas.width  = size;
                        canvas.height = size;
                        canvas.style.cssText = 'border-radius:6px;';
                        wrap.appendChild(canvas);

                        window.QRCode.toCanvas(canvas, url, {
                            width: size,
                            color: { dark: '#ffffff', light: '#111114' },
                            margin: 2,
                        }).catch(() => {});

                        if (qrLabel) {
                            const lbl = document.createElement('div');
                            lbl.style.cssText  = 'font-size:clamp(9px,1.5cqi,12px);color:rgba(245,158,11,0.8);font-weight:500;';
                            lbl.textContent    = qrLabel;
                            wrap.appendChild(lbl);
                        }
                    } else {
                        this._placeholder(el, 'qr');
                        return;
                    }
                    el.appendChild(wrap);
                    break;
                }
            }
        },

        // Generic placeholder for customer canvas nodes without content
        _placeholder(el, type) {
            const p = document.createElement('div');
            p.style.cssText = 'position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;';
            p.innerHTML = `
                <svg viewBox="0 0 20 20" fill="none" style="width:40px;height:40px;color:rgba(245,158,11,0.3)">${this.componentDefs[type] ?? ''}</svg>
                <span style="font-size:10px;color:rgba(161,161,170,0.5);letter-spacing:0.05em;text-transform:uppercase;">${type}</span>`;
            el.appendChild(p);
        },

        // Extract YouTube video ID from various URL formats
        _ytId(url) {
            if (!url) return null;
            const m = url.match(/(?:youtu\.be\/|v=|\/embed\/)([A-Za-z0-9_-]{11})/);
            return m ? m[1] : null;
        },

        _styleRichTextContent(wrap) {
            wrap.querySelectorAll('*').forEach((el) => {
                el.style.maxWidth = '100%';
                el.style.overflowWrap = 'anywhere';
                el.style.wordBreak = 'break-word';
                el.style.whiteSpace = 'normal';
            });

            wrap.querySelectorAll('h1').forEach((el) => {
                el.style.fontSize = 'clamp(22px, 4.5cqi, 40px)';
                el.style.lineHeight = '1.1';
                el.style.fontWeight = '700';
                el.style.color = '#ffffff';
                el.style.margin = '0 0 0.35em';
            });

            wrap.querySelectorAll('h2').forEach((el) => {
                el.style.fontSize = 'clamp(18px, 3.6cqi, 30px)';
                el.style.lineHeight = '1.15';
                el.style.fontWeight = '600';
                el.style.color = '#f4f4f5';
                el.style.margin = '0 0 0.4em';
            });

            wrap.querySelectorAll('p, div').forEach((el) => {
                el.style.margin = '0 0 0.45em';
            });

            wrap.querySelectorAll('ul, ol').forEach((el) => {
                el.style.margin = '0 0 0.6em';
                el.style.paddingLeft = '1.2em';
            });

            wrap.querySelectorAll('li').forEach((el) => {
                el.style.margin = '0 0 0.2em';
            });

            const last = wrap.lastElementChild;

            if (last) {
                last.style.marginBottom = '0';
            }
        },
            }));
        };

        document.addEventListener('alpine:init', register);
        register();
    })();
    </script>
    @endscript
</x-dynamic-component>
