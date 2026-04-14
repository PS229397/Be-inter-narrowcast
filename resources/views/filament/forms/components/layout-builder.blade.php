<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $statePath   = $getStatePath();
        $orientation = $getOrientation() ?? 'landscape';
        $isPortrait  = $orientation === 'portrait';
        $canvasRatio = $isPortrait ? '540 / 940' : '940 / 540';
        $canvasMaxW  = $isPortrait ? '360px' : '100%';
        $canvasMaxH  = $isPortrait ? '640px' : '520px';
    @endphp

    <div
        wire:key="layout-builder-{{ md5($statePath) }}-{{ $orientation }}"
        wire:ignore
        x-data="layoutBuilder({
            state:       $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false) }},
            orientation: @js($orientation)
        })"
        x-init="init()"
        {{ $getExtraAttributeBag() }}
    >
        <div class="grid gap-5" style="grid-template-columns: 1fr 280px;">

            {{-- ─── Canvas ──────────────────────────────────────────────── --}}
            <section
                class="relative overflow-hidden rounded-xl border border-white/8 bg-[#1c1c21] shadow-xl"
                x-on:click="if (!isDragging) { selectedId = null; render(); }"
            >
                <div class="flex min-h-[420px] items-center justify-center p-4">
                    <div
                        style="aspect-ratio: {{ $canvasRatio }}; max-width: {{ $canvasMaxW }}; max-height: {{ $canvasMaxH }};"
                        class="relative isolate w-full overflow-hidden rounded-xl border border-dashed border-amber-400/20 bg-[#111114] shadow-2xl"
                    >
                        <div x-ref="gridContainer" wire:ignore class="absolute inset-0"></div>
                    </div>
                </div>

                {{-- Overlay controls (admin mode only) --}}
                <div x-ref="canvasOverlay" class="pointer-events-none absolute bottom-5 right-5 z-20 hidden">
                    <div class="pointer-events-auto flex gap-2.5">
                        <button x-ref="btnSliceH" type="button" title="Split horizontally"
                            x-on:click.stop="if (selectedId) slice(selectedId, 'h')"
                            class="grid size-10 place-items-center rounded-lg border border-amber-400/40 bg-[#1c1c21]/95 text-slate-100 shadow-lg transition hover:border-amber-300 hover:text-amber-300">
                            <svg class="size-5" viewBox="0 0 20 20" fill="none">
                                <path d="M3 10H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 3"/>
                            </svg>
                        </button>
                        <button x-ref="btnSliceV" type="button" title="Split vertically"
                            x-on:click.stop="if (selectedId) slice(selectedId, 'v')"
                            class="grid size-10 place-items-center rounded-lg border border-amber-400/40 bg-[#1c1c21]/95 text-slate-100 shadow-lg transition hover:border-amber-300 hover:text-amber-300">
                            <svg class="size-5" viewBox="0 0 20 20" fill="none">
                                <path d="M10 3V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 3"/>
                            </svg>
                        </button>
                        <button x-ref="btnDelete" type="button" title="Delete section"
                            x-on:click.stop="if (selectedId) deleteNode(selectedId)"
                            class="grid size-10 place-items-center rounded-lg border border-red-400/40 bg-red-500/10 text-red-300 shadow-lg transition hover:border-red-300 hover:bg-red-500/20 hover:text-red-200">
                            <svg class="size-5" viewBox="0 0 20 20" fill="none">
                                <path d="M7.5 2.75H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M3.75 5.25H16.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M5.75 5.25L6.45 15.1C6.52 16.06 7.31 16.8 8.27 16.8H11.73C12.69 16.8 13.48 16.06 13.55 15.1L14.25 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.5 8.25V13.25M11.5 8.25V13.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>
                        <button type="button" title="Clear canvas"
                            x-on:click.stop="clearCanvas()"
                            class="grid size-10 place-items-center rounded-lg border border-red-400/40 bg-red-500/10 text-red-300 shadow-lg transition hover:border-red-300 hover:bg-red-500/20 hover:text-red-200">
                            <svg class="size-5" viewBox="0 0 20 20" fill="none">
                                <g opacity="0.8">
                                    <path d="M3 5V2.75H6.25M8.5 2.75H11M13.25 2.75H15.75V5M3 7.5V10.25M3 12.75V15.25H5.5M8 15.25H10.25M15.75 7.5V8.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <path d="M12.25 7.75H14.75M10.5 9.75H16.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M11.4 9.75L11.8 16.1C11.84 16.69 12.34 17.15 12.93 17.15H14.07C14.66 17.15 15.16 16.69 15.2 16.1L15.6 9.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13.1 11.5V14.6M13.9 11.5V14.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </section>

            {{-- ─── Right panel ─────────────────────────────────────────── --}}
            <aside
                class="flex flex-col rounded-xl border border-white/8 bg-[#1c1c21] shadow-xl"
                x-on:click.stop
            >
                {{-- ── Admin: component picker ── --}}
                <div x-show="viewMode === 'admin'" class="flex flex-1 flex-col gap-4 overflow-y-auto p-4">
                    <p class="text-sm font-medium text-zinc-400">Base components</p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([
                            ['text',      'Text',      '<path d="M5 5h10M10 5v10M7 15h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                            ['image',     'Image',     '<rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/><circle cx="7.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M2 13.5l4-4 3 3 2.5-2.5 4.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                            ['video',     'Video',     '<rect x="2" y="5" width="12" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M14 8.5l4-2v5l-4-2V8.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>'],
                            ['carousel',  'Carousel',  '<rect x="1" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1.5" opacity="0.4"/><rect x="6" y="3" width="8" height="11" rx="1.5" stroke="currentColor" stroke-width="1.5"/><rect x="15" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1.5" opacity="0.4"/><circle cx="8.5" cy="17.5" r="0.75" fill="currentColor"/><circle cx="10" cy="17.5" r="0.75" fill="currentColor"/><circle cx="11.5" cy="17.5" r="0.75" fill="currentColor"/>'],
                            ['ticker',    'Ticker',    '<rect x="2" y="7" width="16" height="6" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M5 10h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M13 8.5l2.5 1.5-2.5 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                            ['clock',     'Clock',     '<circle cx="10" cy="10" r="7.5" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                            ['weather',   'Weather',   '<circle cx="10" cy="8.5" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M10 3v1M10 14v1M3.5 8.5h1M15.5 8.5h1M5.6 4.6l.7.7M13.7 4.6l-.7.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M5 16a3 3 0 010-6h.5a4 4 0 017 0H13a3 3 0 010 6H5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>'],
                            ['countdown', 'Countdown', '<circle cx="10" cy="11" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M10 8v3l-2.5 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 3h4M10 1v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>'],
                            ['qr',        'QR Code',   '<rect x="3" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/><rect x="12" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/><rect x="3" y="12" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/><rect x="4.5" y="4.5" width="2" height="2" fill="currentColor"/><rect x="13.5" y="4.5" width="2" height="2" fill="currentColor"/><rect x="4.5" y="13.5" width="2" height="2" fill="currentColor"/><path d="M12 12h2v2h-2zM14 14h2v2h-2zM12 16h2M16 12v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                        ] as [$type, $label, $icon])
                            <button
                                data-component="{{ $type }}"
                                type="button"
                                x-on:click.stop="assignComponent('{{ $type }}')"
                                class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-[#24242a] text-zinc-400 transition hover:border-amber-400/40 hover:text-amber-300"
                            >
                                <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">{!! $icon !!}</svg>
                                <span class="text-xs">{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- ── Customer: content input panel ── --}}
                <div x-show="viewMode === 'customer'" class="flex flex-1 flex-col gap-2 overflow-y-auto p-4">

                    {{-- Nothing selected --}}
                    <p x-show="!selectedId" class="text-sm text-zinc-500">
                        Select a section in the layout to edit its content.
                    </p>

                    {{-- Selected but no component --}}
                    <p x-show="selectedId && !getSelectedLeafComponent()" class="text-sm text-zinc-500">
                        No component assigned. Switch to Admin view to assign one.
                    </p>

                    {{-- Per-component input panels --}}
                    @include('filament.forms.components.base-components.text')
                    @include('filament.forms.components.base-components.image')
                    @include('filament.forms.components.base-components.video')
                    @include('filament.forms.components.base-components.carousel')
                    @include('filament.forms.components.base-components.ticker')
                    @include('filament.forms.components.base-components.clock')
                    @include('filament.forms.components.base-components.weather')
                    @include('filament.forms.components.base-components.countdown')
                    @include('filament.forms.components.base-components.qr')
                </div>

                {{-- ── View toggle (footer) ── --}}
                <div class="shrink-0 border-t border-white/8 p-4">
                    <div class="flex gap-2">
                        <button type="button"
                            data-view-toggle
                            :data-active="viewMode === 'admin' ? 'true' : 'false'"
                            x-on:click="viewMode = 'admin'; render()"
                            class="h-9 flex-1 rounded-xl border text-sm font-medium transition">
                            Admin
                        </button>
                        <button type="button"
                            data-view-toggle
                            :data-active="viewMode === 'customer' ? 'true' : 'false'"
                            x-on:click="viewMode = 'customer'; render()"
                            class="h-9 flex-1 rounded-xl border text-sm font-medium transition">
                            Customer
                        </button>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    @once
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

        viewMode:     'admin',
        slideContent: {},
        liveHandles:  [],
        uploadProgress: { image: 0, video: 0, carousel: 0 },

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
        },

        canvasCornerRadius: '0.75rem',
        grid:        null,
        nodeCounter: 0,
        selectedId:  null,
        isDragging:  false,

        // Carousel drag state
        _carouselDragFrom: null,
        _carouselDragOver: null,

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

        // ─── Content helpers ─────────────────────────────────────────────────

        getSelectedLeafComponent() {
            if (!this.selectedId || !this.grid) return null;
            const node = this.findNode(this.grid, this.selectedId);
            if (!node || (node.children ?? []).length > 0) return null;
            return node.component ?? null;
        },

        updateContent(nodeId, key, value) {
            if (!nodeId) return;
            this.slideContent = {
                ...this.slideContent,
                [nodeId]: { ...(this.slideContent[nodeId] ?? {}), [key]: value },
            };
        },

        syncTextEditorContent() {
            const editor = this.$refs.textEditor;

            if (!editor || !this.selectedId) {
                return;
            }

            this.updateContent(this.selectedId, 'html', editor.innerHTML);

            if (this.viewMode === 'customer') {
                this.$nextTick(() => this.render());
            }
        },

        execTextCommand(command, value = null) {
            const editor = this.$refs.textEditor;
            const selection = document.getSelection();

            if (!editor || this.getSelectedLeafComponent() !== 'text') {
                return;
            }

            if (!selection || !editor.contains(selection.anchorNode)) {
                editor.focus();
            }

            if (command === 'foreColor') {
                document.execCommand('styleWithCSS', false, true);
            }

            document.execCommand(command, false, value);
            this.syncTextEditorContent();
        },

        // ─── File uploads (via Livewire WithFileUploads) ─────────────────────

        async handleSingleUpload(nodeId, key, file, progressKey) {
            if (!file) return;
            this.uploadProgress[progressKey] = 1;
            await new Promise((resolve, reject) => {
                this.$wire.upload(
                    'uploadedFile',
                    file,
                    resolve,
                    reject,
                    (e) => { this.uploadProgress[progressKey] = e.detail?.progress ?? 50; }
                );
            });
            this.uploadProgress[progressKey] = 99;
            const url = await this.$wire.persistUpload();
            this.uploadProgress[progressKey] = 0;
            this.updateContent(nodeId, key, url);
            this.$nextTick(() => this.render());
        },

        async handleMultipleUpload(nodeId, key, files, progressKey) {
            if (!files.length) return;
            const urls = [];
            for (let i = 0; i < files.length; i++) {
                this.uploadProgress[progressKey] = Math.round((i / files.length) * 90) + 1;
                await new Promise((resolve, reject) => {
                    this.$wire.upload('uploadedFile', files[i], resolve, reject, () => {});
                });
                const url = await this.$wire.persistUpload();
                urls.push(url);
            }
            this.uploadProgress[progressKey] = 0;
            const current = this.slideContent[nodeId]?.[key] ?? [];
            this.updateContent(nodeId, key, [...current, ...urls]);
            this.$nextTick(() => this.render());
        },

        // ─── Carousel drag-reorder ───────────────────────────────────────────

        carouselDragStart(nodeId, fromIdx) {
            this._carouselDragFrom = fromIdx;
        },

        carouselDragOver(overIdx) {
            this._carouselDragOver = overIdx;
        },

        carouselDrop(nodeId) {
            const from = this._carouselDragFrom;
            const to   = this._carouselDragOver;
            if (from === null || to === null || from === to) return;
            const images = [...(this.slideContent[nodeId]?.images ?? [])];
            const [moved] = images.splice(from, 1);
            images.splice(to, 0, moved);
            this.updateContent(nodeId, 'images', images);
            this._carouselDragFrom = null;
            this._carouselDragOver = null;
            this.$nextTick(() => this.render());
        },

        removeCarouselImage(nodeId, idx) {
            const images = [...(this.slideContent[nodeId]?.images ?? [])];
            images.splice(idx, 1);
            this.updateContent(nodeId, 'images', images);
            this.$nextTick(() => this.render());
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
            // Clear all live component timers
            this.liveHandles.forEach(h => clearInterval(h));
            this.liveHandles = [];

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
                // Overlay only visible in admin mode when something is selected
                overlay.classList.toggle('hidden', this.viewMode === 'customer' || !hasSelection);
                if (this.viewMode === 'admin' && hasSelection) {
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
                const isInteractiveDivider = this.viewMode === 'admin';
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

                if (this.viewMode === 'customer' && node.component) {
                    // Customer mode: render actual component content
                    this._buildComponentEl(el, node);
                } else {
                    // Admin mode: SVG icon or sequential number
                    const center = document.createElement('div');
                    center.style.cssText = 'position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;z-index:4;user-select:none;';
                    if (node.component && this.componentDefs[node.component]) {
                        center.innerHTML = `<svg viewBox="0 0 20 20" fill="none" style="width:56px;height:56px;color:rgba(245,158,11,0.5)">${this.componentDefs[node.component]}</svg>`;
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
    @endonce
</x-dynamic-component>
