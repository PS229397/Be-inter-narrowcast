<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $statePath   = $getStatePath();
        $layout      = $getLayout();
        $orientation = $getOrientation() ?? 'landscape';
        $isPortrait  = $orientation === 'portrait';
        $canvasRatio = $isPortrait ? '540 / 940' : '940 / 540';
        $canvasMaxW  = $isPortrait ? '360px' : '100%';
        $canvasMaxH  = $isPortrait ? '640px' : '520px';
        $gridJson    = $layout?->grid ?? null;
    @endphp

    <div
        wire:key="slide-canvas-{{ md5($statePath) }}-{{ $layout?->getKey() ?? 'none' }}-{{ $orientation }}"
        wire:ignore
        x-data="slideCanvas({
            state:       $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false) }},
            grid:        @js($gridJson),
            orientation: @js($orientation)
        })"
        x-init="init()"
        {{ $getExtraAttributeBag() }}
    >
        {{-- ─── Canvas (read-only preview) ──────────────────────────────── --}}
        <div class="overflow-hidden rounded-xl border border-white/8 bg-[#1c1c21] shadow-xl">
            <div class="flex items-center justify-center p-4" style="min-height: {{ $isPortrait ? '480px' : '360px' }};">
                <div
                    style="aspect-ratio: {{ $canvasRatio }}; max-width: {{ $canvasMaxW }}; max-height: {{ $canvasMaxH }};"
                    class="relative isolate w-full overflow-hidden rounded-xl border border-dashed border-amber-400/20 bg-[#111114] shadow-2xl"
                >
                    <div x-ref="gridContainer" wire:ignore class="absolute inset-0"></div>
                </div>
            </div>
        </div>

        {{-- ─── Content input panel ─────────────────────────────────────── --}}
        <div
            x-ref="contentPanel"
            x-on:click.stop
            class="mt-4 min-h-[200px] rounded-xl border border-white/8 bg-[#1c1c21] p-5 shadow-xl"
        >
            {{-- Nothing selected --}}
            <p
                x-show="!selectedId"
                class="text-sm text-zinc-500"
            >Select a section in the layout to edit its content.</p>

            {{-- Selected but no component assigned --}}
            <p
                x-show="selectedId && !getSelectedLeafComponent()"
                class="text-sm text-zinc-500"
            >No component assigned to this section.</p>

            {{-- ── Text ───────────────────────────────────────────────── --}}
            <div x-show="getSelectedLeafComponent() === 'text'" class="flex h-full flex-col gap-3">
                <p class="text-xs font-medium text-zinc-400">Text content</p>
                <div class="flex flex-1 flex-col overflow-hidden rounded-xl border border-white/10 bg-[#24242a]" style="min-height:180px;">
                    <div class="flex shrink-0 items-center gap-0.5 border-b border-white/10 px-2 py-1.5">
                        <button type="button" class="rounded px-2 py-0.5 text-xs font-bold text-zinc-400 transition hover:bg-white/5 hover:text-white">B</button>
                        <button type="button" class="rounded px-2 py-0.5 text-xs italic text-zinc-400 transition hover:bg-white/5 hover:text-white">I</button>
                        <button type="button" class="rounded px-2 py-0.5 text-xs underline text-zinc-400 transition hover:bg-white/5 hover:text-white">U</button>
                        <div class="mx-1 h-4 w-px bg-white/10"></div>
                        <button type="button" class="rounded px-2 py-0.5 text-xs text-zinc-400 transition hover:bg-white/5 hover:text-white">H1</button>
                        <button type="button" class="rounded px-2 py-0.5 text-xs text-zinc-400 transition hover:bg-white/5 hover:text-white">H2</button>
                        <div class="mx-1 h-4 w-px bg-white/10"></div>
                        <button type="button" class="rounded px-2 py-0.5 text-xs text-zinc-400 transition hover:bg-white/5 hover:text-white">≡</button>
                        <button type="button" class="rounded px-2 py-0.5 text-xs text-zinc-400 transition hover:bg-white/5 hover:text-white">⁝≡</button>
                    </div>
                    <div
                        contenteditable="true"
                        class="min-h-[120px] flex-1 p-3 text-sm text-zinc-300 outline-none"
                        x-on:input="updateContent(selectedId, 'text', $event.target.innerHTML)"
                        x-effect="if (getSelectedLeafComponent() === 'text') $el.innerHTML = (slideContent[selectedId]?.text ?? '')"
                    ></div>
                </div>
            </div>

            {{-- ── Image ──────────────────────────────────────────────── --}}
            <div x-show="getSelectedLeafComponent() === 'image'">
                <label class="flex min-h-[160px] cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-[#24242a] transition hover:border-amber-400/40">
                    <svg class="size-7 text-zinc-500" viewBox="0 0 20 20" fill="none">
                        <path d="M10 4v8M6 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span class="text-xs text-zinc-400">Click to upload or drag &amp; drop · PNG, JPG, WebP</span>
                    <input type="file" accept="image/*" class="hidden">
                </label>
            </div>

            {{-- ── Video ──────────────────────────────────────────────── --}}
            <div x-show="getSelectedLeafComponent() === 'video'">
                <label class="flex min-h-[160px] cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-[#24242a] transition hover:border-amber-400/40">
                    <svg class="size-7 text-zinc-500" viewBox="0 0 20 20" fill="none">
                        <path d="M10 4v8M6 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span class="text-xs text-zinc-400">Click to upload or drag &amp; drop · MP4, WebM</span>
                    <input type="file" accept="video/*" class="hidden">
                </label>
            </div>

            {{-- ── Carousel ───────────────────────────────────────────── --}}
            <div x-show="getSelectedLeafComponent() === 'carousel'" class="flex flex-col gap-3">
                <p class="text-xs font-medium text-zinc-400">Slides</p>
                <label class="flex min-h-[160px] cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-[#24242a] transition hover:border-amber-400/40">
                    <svg class="size-7 text-zinc-500" viewBox="0 0 20 20" fill="none">
                        <path d="M10 4v8M6 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span class="text-xs text-zinc-400">Add images · PNG, JPG, WebP</span>
                    <span class="text-xs text-zinc-600">Each uploaded image becomes a slide.</span>
                    <input type="file" accept="image/*" multiple class="hidden">
                </label>
            </div>

            {{-- ── Ticker ─────────────────────────────────────────────── --}}
            <div x-show="getSelectedLeafComponent() === 'ticker'" class="flex flex-col gap-3">
                <p class="text-xs font-medium text-zinc-400">Scrolling text</p>
                <textarea
                    placeholder="Breaking news · Type your message here..."
                    class="w-full resize-none rounded-xl border border-white/10 bg-[#24242a] px-4 py-3 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
                    style="min-height:80px;"
                    x-on:input="updateContent(selectedId, 'text', $event.target.value)"
                    x-effect="if (getSelectedLeafComponent() === 'ticker') $el.value = (slideContent[selectedId]?.text ?? '')"
                ></textarea>
                <div class="flex flex-col gap-1.5">
                    <span class="text-xs font-medium text-zinc-400">Speed</span>
                    <input type="range" min="1" max="10" value="5" class="w-full accent-amber-400">
                </div>
            </div>

            {{-- ── Clock ──────────────────────────────────────────────── --}}
            <div x-show="getSelectedLeafComponent() === 'clock'" class="flex flex-col gap-4">
                <div class="flex flex-col gap-1.5">
                    <span class="text-xs font-medium text-zinc-400">Time zone</span>
                    <select class="h-11 w-full appearance-none rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition focus:border-amber-400/60">
                        <option>Europe/Amsterdam</option>
                        <option>Europe/London</option>
                        <option>America/New_York</option>
                        <option>America/Los_Angeles</option>
                        <option>Asia/Tokyo</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1.5">
                    <span class="text-xs font-medium text-zinc-400">Format</span>
                    <select class="h-11 w-full appearance-none rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition focus:border-amber-400/60">
                        <option>HH:mm:ss (24h)</option>
                        <option>HH:mm (24h)</option>
                        <option>hh:mm a (12h)</option>
                    </select>
                </div>
            </div>

            {{-- ── Weather ────────────────────────────────────────────── --}}
            <div x-show="getSelectedLeafComponent() === 'weather'" class="flex flex-col gap-4">
                <div class="flex flex-col gap-1.5">
                    <span class="text-xs font-medium text-zinc-400">Location</span>
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Amsterdam, Netherlands"
                            class="h-11 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 pr-10 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
                        >
                        <svg class="pointer-events-none absolute right-3 top-1/2 size-4 -translate-y-1/2 text-zinc-500" viewBox="0 0 20 20" fill="none">
                            <path d="M10 2a6 6 0 016 6c0 4-6 10-6 10S4 12 4 8a6 6 0 016-6z" stroke="currentColor" stroke-width="1.5"/>
                            <circle cx="10" cy="8" r="2" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <span class="text-xs font-medium text-zinc-400">Unit</span>
                    <select class="h-11 w-full appearance-none rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition focus:border-amber-400/60">
                        <option>°C — Celsius</option>
                        <option>°F — Fahrenheit</option>
                    </select>
                </div>
            </div>

            {{-- ── Countdown ──────────────────────────────────────────── --}}
            <div x-show="getSelectedLeafComponent() === 'countdown'" class="flex flex-col gap-4">
                <div class="flex flex-col gap-1.5">
                    <span class="text-xs font-medium text-zinc-400">Target date</span>
                    <input type="date" class="h-11 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition focus:border-amber-400/60">
                </div>
                <div class="flex flex-col gap-1.5">
                    <span class="text-xs font-medium text-zinc-400">Target time</span>
                    <input type="time" class="h-11 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition focus:border-amber-400/60">
                </div>
                <div class="flex flex-col gap-1.5">
                    <span class="text-xs font-medium text-zinc-400">Label</span>
                    <input type="text" placeholder="Event starts in..." class="h-11 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60">
                </div>
            </div>

            {{-- ── QR Code ────────────────────────────────────────────── --}}
            <div x-show="getSelectedLeafComponent() === 'qr'" class="flex flex-col gap-1.5">
                <span class="text-xs font-medium text-zinc-400">URL</span>
                <input type="url" placeholder="https://example.com" class="h-11 w-full rounded-xl border border-white/10 bg-[#24242a] px-4 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60">
            </div>

        </div>
    </div>

    @once
    <script>
        (() => {
            const register = () => {
                if (! window.Alpine || window.__slideCanvasRegistered) {
                    return;
                }

                window.__slideCanvasRegistered = true;

                Alpine.data('slideCanvas', (config) => ({
            state:        config.state,
            orientation:  config.orientation ?? 'landscape',
            grid:         null,
            slideContent: {},
            selectedId:   null,
            isDragging:   false,

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

            // ─── Lifecycle ───────────────────────────────────────────────

            init() {
                // Parse grid from layout (read-only, comes from PHP)
                const rawGrid = config.grid;
                if (rawGrid) {
                    try {
                        this.grid = typeof rawGrid === 'string' ? JSON.parse(rawGrid) : JSON.parse(JSON.stringify(rawGrid));
                    } catch (_) {}
                }

                // Parse existing slide_content from entangled state
                const rawState = this.state;
                if (rawState) {
                    this.slideContent = typeof rawState === 'string' ? JSON.parse(rawState) : (rawState ?? {});
                }

                // Sync outward when slideContent mutates
                this.$watch('slideContent', (val) => {
                    this.state = val ?? {};
                });

                // Sync inward from external Livewire updates
                this.$watch('state', (val) => {
                    if (!val) { this.slideContent = {}; return; }
                    const incoming = typeof val === 'string' ? JSON.parse(val) : val;
                    if (JSON.stringify(incoming) !== JSON.stringify(this.slideContent)) {
                        this.slideContent = incoming;
                    }
                });

                this.$nextTick(() => this.render());
            },

            // ─── Helpers ─────────────────────────────────────────────────

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

            findNode(node, id) {
                if (node.id === id) return node;
                for (const c of (node.children ?? [])) {
                    const hit = this.findNode(c, id);
                    if (hit) return hit;
                }
                return null;
            },

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

            // ─── Rendering ───────────────────────────────────────────────

            render() {
                const container = this.$refs.gridContainer;
                if (!container || !this.grid) return;

                container.innerHTML = '';
                const rootEl = this.buildEl(this.grid, 100, 100, this.leafOrder(this.grid));
                rootEl.style.position = 'absolute';
                rootEl.style.inset    = '0';
                container.appendChild(rootEl);
            },

            buildEl(
                node,
                wPct    = 100,
                hPct    = 100,
                order   = new Map(),
                corners = { topLeft: true, topRight: true, bottomRight: true, bottomLeft: true },
            ) {
                const el = document.createElement('div');
                el.dataset.nodeId = node.id;
                el.style.cssText  = 'width:100%; height:100%; position:relative; overflow:hidden; box-sizing:border-box;';

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
                        ? `flex: 0 0 ${split}%; min-height:0; position:relative; overflow:hidden;`
                        : `flex: 0 0 ${split}%; min-width:0;  position:relative; overflow:hidden;`;

                    // Static divider (no drag in read-only mode)
                    const divider = document.createElement('div');
                    divider.style.cssText = isH
                        ? 'flex:0 0 1px; background:rgba(245,158,11,0.15);'
                        : 'flex:0 0 1px; background:rgba(245,158,11,0.15);';

                    const w2 = document.createElement('div');
                    w2.style.cssText = isH
                        ? 'flex:1; min-height:0; position:relative; overflow:hidden;'
                        : 'flex:1; min-width:0;  position:relative; overflow:hidden;';

                    w1.appendChild(this.buildEl(node.children[0], isH ? wPct : wPct * split / 100,         isH ? hPct * split / 100         : hPct, order, child1Corners));
                    w2.appendChild(this.buildEl(node.children[1], isH ? wPct : wPct * (100 - split) / 100, isH ? hPct * (100 - split) / 100 : hPct, order, child2Corners));
                    el.appendChild(w1);
                    el.appendChild(divider);
                    el.appendChild(w2);

                } else {
                    this.applyCornerRadius(el, corners);

                    const center = document.createElement('div');
                    center.style.cssText = 'position:absolute; inset:0; display:flex; align-items:center; justify-content:center; pointer-events:none; z-index:4; user-select:none;';
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
                    pill.style.cssText    = 'position:absolute; bottom:5px; left:6px; font-size:10px; line-height:1; color:rgba(161,161,170,0.7); background:rgba(17,17,20,0.7); border:1px solid rgba(161,161,170,0.12); border-radius:999px; padding:3px 8px; pointer-events:none; z-index:5; user-select:none; font-family:monospace; white-space:nowrap;';
                    el.appendChild(pill);

                    el.style.backgroundColor = 'rgba(17,17,20,0.55)';
                    el.style.cursor          = 'pointer';
                    el.style.transition      = 'background-color 0.15s';

                    el.addEventListener('mouseenter', () => {
                        if (node.id !== this.selectedId)
                            el.style.backgroundColor = 'rgba(245,158,11,0.10)';
                    });
                    el.addEventListener('mouseleave', () => {
                        if (node.id !== this.selectedId)
                            el.style.backgroundColor = 'rgba(17,17,20,0.55)';
                    });

                    el.addEventListener('click', e => {
                        e.stopPropagation();
                        this.selectedId = node.id;
                        this.render();
                    });
                }

                if (node.id === this.selectedId) {
                    el.style.outline       = '2px solid rgba(245,158,11,0.75)';
                    el.style.outlineOffset = '-2px';
                }

                return el;
            },
                }));
            };

            document.addEventListener('alpine:init', register);
            register();
        })();
    </script>
    @endonce
</x-dynamic-component>
