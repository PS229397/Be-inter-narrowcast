<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout Preview</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-50">
    <main
        id="layout-preview"
        class="mx-auto grid min-h-screen w-full grid-cols-1 gap-[20px] px-4 py-8 lg:py-16 xl:w-[1440px] xl:max-w-[1440px] xl:grid-cols-[var(--section-1-width)_var(--section-2-width)] xl:px-0 xl:items-center xl:justify-center"
    >
        <section class="relative w-full rounded-xl border border-white/10 bg-slate-900 shadow-2xl shadow-cyan-950/40 xl:h-[var(--preview-height)] xl:w-[var(--section-1-width)]">
            <div class="grid h-full place-items-center p-4 sm:p-5">
                <div
                    id="canvas-stage"
                    style="max-width: var(--canvas-width); aspect-ratio: var(--canvas-width) / var(--canvas-height);"
                    class="relative isolate w-full overflow-hidden rounded-xl border border-dashed border-cyan-400/30 bg-slate-950/70 shadow-2xl shadow-cyan-950/20 transition xl:h-[var(--canvas-height)] xl:w-[var(--canvas-width)]"
                >
                    <div id="grid-container" class="absolute inset-0"></div>

                    <div id="canvas-overlay" class="pointer-events-none absolute inset-0 z-20 hidden">
                        <div class="pointer-events-auto absolute bottom-5 right-5 flex gap-2.5">
                            <button id="btn-slice-h" type="button" class="grid size-10 place-items-center rounded-lg border border-cyan-400/40 bg-slate-900/95 text-slate-100 shadow-lg shadow-slate-950/60 transition hover:border-cyan-300 hover:text-cyan-300">
                                <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M3 10H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 3" />
                                </svg>
                            </button>
                            <button id="btn-slice-v" type="button" class="grid size-10 place-items-center rounded-lg border border-cyan-400/40 bg-slate-900/95 text-slate-100 shadow-lg shadow-slate-950/60 transition hover:border-cyan-300 hover:text-cyan-300">
                                <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M10 3V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 3" />
                                </svg>
                            </button>
                            <button id="btn-delete" type="button" class="grid size-10 place-items-center rounded-lg border border-cyan-400/40 bg-slate-900/95 text-slate-100 shadow-lg shadow-slate-950/60 transition hover:border-cyan-300 hover:text-cyan-300">
                                <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M7.5 2.75H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M3.75 5.25H16.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M5.75 5.25L6.45 15.1C6.52 16.06 7.31 16.8 8.27 16.8H11.73C12.69 16.8 13.48 16.06 13.55 15.1L14.25 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8.5 8.25V13.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M11.5 8.25V13.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid w-full gap-5 xl:h-[var(--preview-height)] xl:w-[var(--section-2-width)] xl:grid-rows-[230px_minmax(0,1fr)]">
            <div class="rounded-xl border border-white/10 bg-slate-900 p-4 shadow-2xl shadow-cyan-950/40 sm:p-5 xl:h-[230px] xl:w-[var(--section-2-width)] xl:p-5">
                <div class="grid h-full grid-rows-[auto_auto_1fr] gap-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="grid gap-2">
                            <span class="text-sm font-medium text-slate-200">Title</span>
                            <input
                                type="text"
                                value="Test"
                                class="h-11 rounded-xl border border-white/10 bg-slate-950 px-4 text-sm text-white outline-none transition placeholder:text-slate-500 focus:border-cyan-400/60"
                            >
                        </label>

                        <label class="grid gap-2">
                            <span class="text-sm font-medium text-slate-200">Orientation</span>
                            <div class="relative">
                                <select id="orientation-select" class="h-11 w-full appearance-none rounded-xl border border-white/10 bg-slate-950 px-4 pr-11 text-sm text-white outline-none transition focus:border-cyan-400/60">
                                    <option value="landscape" selected>Landscape</option>
                                    <option value="portrait">Portrait</option>
                                </select>
                                <svg class="pointer-events-none absolute right-4 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </label>
                    </div>

                    <div class="grid items-end gap-4 sm:grid-cols-2">
                        <label class="grid gap-2">
                            <span class="text-sm font-medium text-slate-200">Customer</span>
                            <div class="relative">
                                <select class="h-11 w-full appearance-none rounded-xl border border-white/10 bg-slate-950 px-4 pr-11 text-sm text-white outline-none transition focus:border-cyan-400/60">
                                    <option selected>All customers</option>
                                    <option>Be-interactive</option>
                                </select>
                                <svg class="pointer-events-none absolute right-4 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </label>

                        <div class="flex gap-2">
                            <button id="btn-save" type="button" class="h-11 flex-1 rounded-xl border border-cyan-400/40 bg-cyan-500/10 text-sm font-medium text-cyan-300 transition hover:border-cyan-300 hover:bg-cyan-500/20">Save</button>
                            <button type="button" class="grid h-11 w-11 shrink-0 place-items-center rounded-xl border border-red-400/30 bg-red-500/10 text-red-400 transition hover:border-red-400 hover:bg-red-500/20">
                                <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M7.5 2.75H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M3.75 5.25H16.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M5.75 5.25L6.45 15.1C6.52 16.06 7.31 16.8 8.27 16.8H11.73C12.69 16.8 13.48 16.06 13.55 15.1L14.25 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8.5 8.25V13.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M11.5 8.25V13.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p class="self-end text-xs text-slate-400">
                        Leave customer empty to make the layout available to all customers.
                    </p>
                </div>
            </div>
            <div class="h-full rounded-xl border border-white/10 bg-slate-900 p-6 shadow-2xl shadow-cyan-950/40 sm:p-8 xl:w-[var(--section-2-width)] xl:p-10">
                <p>component select</p>
            </div>
        </section>
    </main>

    <script>
        (() => {
            // --- DOM ---
            const preview        = document.getElementById('layout-preview');
            const orientationSelect = document.getElementById('orientation-select');
            const gridContainer  = document.getElementById('grid-container');
            const canvasOverlay  = document.getElementById('canvas-overlay');
            const btnSliceH      = document.getElementById('btn-slice-h');
            const btnSliceV      = document.getElementById('btn-slice-v');
            const btnDelete      = document.getElementById('btn-delete');
            const btnSave        = document.getElementById('btn-save');

            // --- Orientation ---
            const sizes = {
                landscape: { width: 940, height: 540 },
                portrait:  { width: 540, height: 940 },
            };

            const realSizes = {
                landscape: { width: 1920, height: 1080 },
                portrait:  { width: 1080, height: 1920 },
            };

            preview.style.setProperty('--preview-height',   '1000px');
            preview.style.setProperty('--section-1-width',  '1000px');
            preview.style.setProperty('--section-2-width',   '420px');

            const applyOrientation = (orientation) => {
                const size = sizes[orientation] ?? sizes.landscape;
                preview.style.setProperty('--canvas-width',  `${size.width}px`);
                preview.style.setProperty('--canvas-height', `${size.height}px`);
            };

            // --- State ---
            let nodeCounter = 0;
            const makeNode = () => ({ id: `n${++nodeCounter}`, direction: null, split: 50, children: [] });
            let grid = { id: 'root', direction: null, split: 50, children: [] };
            let selectedId = null;
            let isDragging = false;

            // --- Tree helpers ---
            function findNode(node, id) {
                if (node.id === id) return node;
                for (const c of node.children) {
                    const hit = findNode(c, id);
                    if (hit) return hit;
                }
                return null;
            }

            function findParent(node, id) {
                for (const c of node.children) {
                    if (c.id === id) return node;
                    const hit = findParent(c, id);
                    if (hit) return hit;
                }
                return null;
            }

            // --- Actions ---
            function slice(nodeId, direction) {
                const node = findNode(grid, nodeId);
                if (!node || node.children.length > 0) return; // leaves only
                node.direction = direction;
                node.split     = 50;
                node.children  = [makeNode(), makeNode()];
                selectedId = node.children[0].id;
                render();
            }

            function deleteNode(nodeId) {
                if (nodeId === 'root') return;
                const parent = findParent(grid, nodeId);
                if (!parent) return;
                parent.children = parent.children.filter(c => c.id !== nodeId);
                // Promote the surviving sibling up into the parent slot
                if (parent.children.length === 1) {
                    const survivor    = parent.children[0];
                    parent.direction  = survivor.direction;
                    parent.children   = survivor.children;
                }
                selectedId = null;
                render();
            }

            // --- Render ---
            const fmtPill = (wPct, hPct) => {
                const real = realSizes[orientationSelect.value] ?? realSizes.landscape;
                return `${Math.round(real.width * wPct / 100)}×${Math.round(real.height * hPct / 100)}px`;
            };

            function leafOrder(node, map = new Map(), counter = { n: 0 }) {
                if (node.children.length === 0) {
                    map.set(node.id, ++counter.n);
                } else {
                    for (const c of node.children) leafOrder(c, map, counter);
                }
                return map;
            }

            function buildEl(node, wPct = 100, hPct = 100, order = new Map()) {
                const el = document.createElement('div');
                el.dataset.nodeId = node.id;
                el.style.cssText  = 'width:100%; height:100%; position:relative; overflow:hidden; box-sizing:border-box;';

                if (node.children.length > 0) {
                    const isH  = node.direction === 'h';
                    const split = node.split ?? 50;

                    el.style.display       = 'flex';
                    el.style.flexDirection = isH ? 'column' : 'row';

                    // Child 1 — sized by split %
                    const w1 = document.createElement('div');
                    w1.style.cssText = isH
                        ? `flex: 0 0 ${split}%; min-height:0; position:relative; overflow:hidden;`
                        : `flex: 0 0 ${split}%; min-width:0; position:relative; overflow:hidden;`;

                    // Drag handle
                    const handle = document.createElement('div');
                    handle.style.cssText = isH
                        ? 'box-sizing:content-box; flex:0 0 1px; padding:7px 0; margin:-7px 0; cursor:row-resize; position:relative; z-index:10; display:flex; align-items:center;'
                        : 'box-sizing:content-box; flex:0 0 1px; padding:0 7px; margin:0 -7px; cursor:col-resize; position:relative; z-index:10; display:flex; justify-content:center;';

                    const dashIdle   = 'rgba(34,211,238,0.3)';
                    const dashHover  = 'rgba(34,211,238,0.7)';
                    const dashActive = 'rgba(34,211,238,1)';
                    const dashedBg = (color, dir) => dir === 'h'
                        ? `repeating-linear-gradient(to right, ${color} 0px, ${color} 4px, transparent 4px, transparent 8px)`
                        : `repeating-linear-gradient(to bottom, ${color} 0px, ${color} 4px, transparent 4px, transparent 8px)`;

                    const line = document.createElement('div');
                    line.style.cssText = isH
                        ? 'width:100%; height:1px; transition:opacity 0.15s;'
                        : 'height:100%; width:1px; transition:opacity 0.15s;';
                    line.style.background = dashedBg(dashIdle, node.direction);
                    handle.appendChild(line);

                    handle.addEventListener('mouseenter', () => { line.style.background = dashedBg(dashHover, node.direction); });
                    handle.addEventListener('mouseleave', () => { if (!handle._drag) line.style.background = dashedBg(dashIdle, node.direction); });
                    handle.addEventListener('click', e => e.stopPropagation());

                    handle.addEventListener('mousedown', e => {
                        e.stopPropagation();
                        e.preventDefault();
                        handle._drag = true;
                        isDragging = true;
                        line.style.background = dashedBg(dashActive, node.direction);

                        const rect = el.getBoundingClientRect();

                        const onMove = e => {
                            const raw = isH
                                ? (e.clientY - rect.top)  / rect.height * 100
                                : (e.clientX - rect.left) / rect.width  * 100;

                            const snapped = Math.round(raw / 5) * 5;
                            const clamped = Math.max(5, Math.min(95, snapped));

                            w1.style.flex = `0 0 ${clamped}%`;
                            node.split    = clamped;

                            const lbl1 = w1.querySelector('[data-pct-label]');
                            const lbl2 = w2.querySelector('[data-pct-label]');
                            if (lbl1) lbl1.textContent = fmtPill(
                                isH ? wPct : wPct * clamped / 100,
                                isH ? hPct * clamped / 100 : hPct
                            );
                            if (lbl2) lbl2.textContent = fmtPill(
                                isH ? wPct : wPct * (100 - clamped) / 100,
                                isH ? hPct * (100 - clamped) / 100 : hPct
                            );
                        };

                        const onUp = () => {
                            handle._drag = false;
                            isDragging = false;
                            line.style.background = dashedBg(dashIdle, node.direction);
                            document.removeEventListener('mousemove', onMove);
                            document.removeEventListener('mouseup',   onUp);
                        };

                        document.addEventListener('mousemove', onMove);
                        document.addEventListener('mouseup',   onUp);
                    });

                    // Child 2 — takes remaining space
                    const w2 = document.createElement('div');
                    w2.style.cssText = isH
                        ? 'flex:1; min-height:0; position:relative; overflow:hidden;'
                        : 'flex:1; min-width:0; position:relative; overflow:hidden;';

                    w1.appendChild(buildEl(node.children[0],
                        isH ? wPct : wPct * split / 100,
                        isH ? hPct * split / 100 : hPct,
                        order
                    ));
                    w2.appendChild(buildEl(node.children[1],
                        isH ? wPct : wPct * (100 - split) / 100,
                        isH ? hPct * (100 - split) / 100 : hPct,
                        order
                    ));
                    el.appendChild(w1);
                    el.appendChild(handle);
                    el.appendChild(w2);
                } else {
                    const orderLabel = document.createElement('div');
                    orderLabel.textContent = order.get(node.id) ?? '';
                    orderLabel.style.cssText = 'position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-size:48px; font-weight:700; color:rgba(34,211,238,0.35); pointer-events:none; z-index:4; user-select:none; font-family:monospace;';
                    el.appendChild(orderLabel);

                    const pill = document.createElement('div');
                    pill.dataset.pctLabel = '';
                    pill.textContent = fmtPill(wPct, hPct);
                    pill.style.cssText = 'position:absolute; bottom:5px; left:6px; font-size:10px; line-height:1; color:rgba(148,163,184,0.7); background:rgba(2,6,23,0.6); border:1px solid rgba(148,163,184,0.15); border-radius:999px; padding:3px 8px; pointer-events:none; z-index:5; user-select:none; font-family:monospace; white-space:nowrap;';
                    el.appendChild(pill);

                    el.style.backgroundColor = 'rgba(2,6,23,0.55)';
                    el.style.cursor          = 'pointer';
                    el.style.transition      = 'background-color 0.15s';

                    el.addEventListener('mouseenter', () => {
                        if (!isDragging && node.id !== selectedId) el.style.backgroundColor = 'rgba(8,145,178,0.10)';
                    });
                    el.addEventListener('mouseleave', () => {
                        if (!isDragging && node.id !== selectedId) el.style.backgroundColor = 'rgba(2,6,23,0.55)';
                    });
                }

                if (node.id === selectedId) {
                    el.style.outline       = '2px solid rgba(34,211,238,0.75)';
                    el.style.outlineOffset = '-2px';
                }

                el.addEventListener('click', e => {
                    e.stopPropagation();
                    selectedId = node.id;
                    render();
                });

                return el;
            }

            function render() {
                gridContainer.innerHTML = '';
                const rootEl = buildEl(grid, 100, 100, leafOrder(grid));
                rootEl.style.position = 'absolute';
                rootEl.style.inset    = '0';
                gridContainer.appendChild(rootEl);

                // Controls visibility
                const hasSelection = selectedId !== null;
                canvasOverlay.classList.toggle('hidden', !hasSelection);

                if (hasSelection) {
                    const node   = findNode(grid, selectedId);
                    const isLeaf = node && node.children.length === 0;
                    btnSliceH.classList.toggle('hidden', !isLeaf);
                    btnSliceV.classList.toggle('hidden', !isLeaf);
                    btnDelete.classList.toggle('hidden', selectedId === 'root');
                }
            }

            // --- Control button events ---
            btnSliceH.addEventListener('click', e => { e.stopPropagation(); if (selectedId) slice(selectedId, 'h'); });
            btnSliceV.addEventListener('click', e => { e.stopPropagation(); if (selectedId) slice(selectedId, 'v'); });
            btnDelete.addEventListener('click', e => { e.stopPropagation(); if (selectedId) deleteNode(selectedId); });
            btnSave.addEventListener('click',   () => console.log('grid JSON:', JSON.stringify(grid, null, 2)));

            // Deselect on outside click
            document.addEventListener('click', () => { selectedId = null; render(); });

            // --- Init ---
            orientationSelect.addEventListener('change', e => applyOrientation(e.target.value));
            applyOrientation(orientationSelect.value);
            render();
        })();
    </script>
</body>
</html>
