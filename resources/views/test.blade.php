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
                </div>
            </div>

            <div id="canvas-overlay" class="pointer-events-none absolute bottom-5 right-5 z-20 hidden">
                <div class="pointer-events-auto flex gap-2.5">
                    <button id="btn-slice-h" type="button" title="Split horizontally" aria-label="Split horizontally" class="grid size-10 place-items-center rounded-lg border border-cyan-400/40 bg-slate-900/95 text-slate-100 shadow-lg shadow-slate-950/60 transition hover:border-cyan-300 hover:text-cyan-300">
                        <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M3 10H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 3" />
                        </svg>
                    </button>
                    <button id="btn-slice-v" type="button" title="Split vertically" aria-label="Split vertically" class="grid size-10 place-items-center rounded-lg border border-cyan-400/40 bg-slate-900/95 text-slate-100 shadow-lg shadow-slate-950/60 transition hover:border-cyan-300 hover:text-cyan-300">
                        <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 3V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 3" />
                        </svg>
                    </button>
                    <button id="btn-delete" type="button" title="Delete section" aria-label="Delete section" class="grid size-10 place-items-center rounded-lg border border-red-400/40 bg-red-500/10 text-red-300 shadow-lg shadow-slate-950/60 transition hover:border-red-300 hover:bg-red-500/20 hover:text-red-200">
                        <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M7.5 2.75H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M3.75 5.25H16.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M5.75 5.25L6.45 15.1C6.52 16.06 7.31 16.8 8.27 16.8H11.73C12.69 16.8 13.48 16.06 13.55 15.1L14.25 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8.5 8.25V13.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M11.5 8.25V13.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                    <button id="btn-delete-all" type="button" title="Delete all sections" aria-label="Delete all sections" class="grid size-10 place-items-center rounded-lg border border-red-400/40 bg-red-500/10 text-red-300 shadow-lg shadow-slate-950/60 transition hover:border-red-300 hover:bg-red-500/20 hover:text-red-200">
                        <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <g opacity="0.8">
                                <path d="M3 5V2.75H6.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8.5 2.75H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M13.25 2.75H15.75V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3 7.5V10.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M3 12.75V15.25H5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8 15.25H10.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M15.75 7.5V8.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </g>
                            <path d="M12.25 7.75H14.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M10.5 9.75H16.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M11.4 9.75L11.8 16.1C11.84 16.69 12.34 17.15 12.93 17.15H14.07C14.66 17.15 15.16 16.69 15.2 16.1L15.6 9.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M13.1 11.5V14.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M13.9 11.5V14.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
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
            <div class="h-full rounded-xl border border-white/10 bg-slate-900 p-5 shadow-2xl shadow-cyan-950/40 xl:w-[var(--section-2-width)] flex flex-col">
                <!-- Admin panel -->
                <div id="panel-admin" class="flex flex-col gap-4 flex-1 min-h-0 overflow-y-auto">
                    <p class="text-sm font-medium text-slate-400">Base components</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button data-component="text" type="button" class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-slate-950 text-slate-400 transition hover:border-cyan-400/40 hover:text-cyan-300">
                            <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M5 5h10M10 5v10M7 15h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-xs">Text</span>
                        </button>
                        <button data-component="image" type="button" class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-slate-950 text-slate-400 transition hover:border-cyan-400/40 hover:text-cyan-300">
                            <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                <circle cx="7.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M2 13.5l4-4 3 3 2.5-2.5 4.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-xs">Image</span>
                        </button>
                        <button data-component="video" type="button" class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-slate-950 text-slate-400 transition hover:border-cyan-400/40 hover:text-cyan-300">
                            <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <rect x="2" y="5" width="12" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M14 8.5l4-2v5l-4-2V8.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-xs">Video</span>
                        </button>
                        <button data-component="carousel" type="button" class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-slate-950 text-slate-400 transition hover:border-cyan-400/40 hover:text-cyan-300">
                            <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <rect x="1" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                                <rect x="6" y="3" width="8" height="11" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                                <rect x="15" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                                <circle cx="8.5" cy="17.5" r="0.75" fill="currentColor"/>
                                <circle cx="10" cy="17.5" r="0.75" fill="currentColor"/>
                                <circle cx="11.5" cy="17.5" r="0.75" fill="currentColor"/>
                            </svg>
                            <span class="text-xs">Carousel</span>
                        </button>
                        <button data-component="ticker" type="button" class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-slate-950 text-slate-400 transition hover:border-cyan-400/40 hover:text-cyan-300">
                            <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <rect x="2" y="7" width="16" height="6" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M5 10h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M13 8.5l2.5 1.5-2.5 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-xs">Ticker</span>
                        </button>
                        <button data-component="clock" type="button" class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-slate-950 text-slate-400 transition hover:border-cyan-400/40 hover:text-cyan-300">
                            <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <circle cx="10" cy="10" r="7.5" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-xs">Clock</span>
                        </button>
                        <button data-component="weather" type="button" class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-slate-950 text-slate-400 transition hover:border-cyan-400/40 hover:text-cyan-300">
                            <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <circle cx="10" cy="8.5" r="3" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M10 3v1M10 14v1M3.5 8.5h1M15.5 8.5h1M5.6 4.6l.7.7M13.7 4.6l-.7.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M5 16a3 3 0 010-6h.5a4 4 0 017 0H13a3 3 0 010 6H5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-xs">Weather</span>
                        </button>
                        <button data-component="countdown" type="button" class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-slate-950 text-slate-400 transition hover:border-cyan-400/40 hover:text-cyan-300">
                            <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <circle cx="10" cy="11" r="7" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M10 8v3l-2.5 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8 3h4M10 1v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span class="text-xs">Countdown</span>
                        </button>
                        <button data-component="qr" type="button" class="flex aspect-square flex-col items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-slate-950 text-slate-400 transition hover:border-cyan-400/40 hover:text-cyan-300">
                            <svg class="size-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <rect x="3" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/>
                                <rect x="12" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/>
                                <rect x="3" y="12" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/>
                                <rect x="4.5" y="4.5" width="2" height="2" fill="currentColor"/>
                                <rect x="13.5" y="4.5" width="2" height="2" fill="currentColor"/>
                                <rect x="4.5" y="13.5" width="2" height="2" fill="currentColor"/>
                                <path d="M12 12h2v2h-2zM14 14h2v2h-2zM12 16h2M16 12v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-xs">QR Code</span>
                        </button>
                    </div>
                </div>

                <!-- Customer panel -->
                <div id="panel-customer" class="hidden flex-1 min-h-0 flex flex-col overflow-y-auto">
                    <div id="customer-input" class="flex flex-col flex-1 min-h-0">
                        <p class="text-sm text-slate-500">Select a section in the layout to edit its content.</p>
                    </div>
                </div>

                <!-- View toggle -->
                <div class="mt-auto flex gap-2 pt-4 border-t border-white/10 shrink-0">
                    <button id="btn-view-admin" type="button" class="h-9 flex-1 rounded-xl border border-cyan-400/40 bg-cyan-500/10 text-sm font-medium text-cyan-300 transition hover:bg-cyan-500/20">Admin</button>
                    <button id="btn-view-customer" type="button" class="h-9 flex-1 rounded-xl border border-white/10 bg-slate-950 text-sm font-medium text-slate-400 transition hover:border-white/20 hover:text-slate-200">Customer</button>
                </div>
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
            const btnDeleteAll   = document.getElementById('btn-delete-all');
            const btnSave        = document.getElementById('btn-save');
            const btnViewAdmin   = document.getElementById('btn-view-admin');
            const btnViewCustomer= document.getElementById('btn-view-customer');
            const panelAdmin     = document.getElementById('panel-admin');
            const panelCustomer  = document.getElementById('panel-customer');
            const customerInput  = document.getElementById('customer-input');

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
            const makeNode = () => ({ id: `n${++nodeCounter}`, direction: null, split: 50, children: [], component: null });
            let grid = { id: 'root', direction: null, split: 50, children: [], component: null };
            let selectedId       = null;
            let isDragging      = false;
            let viewMode        = 'admin';
            let lastCustomerKey = null;

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
                const inherited = node.component;
                node.direction  = direction;
                node.split      = 50;
                node.component  = null;
                node.children   = [makeNode(), makeNode()];
                node.children[0].component = inherited;
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
                    parent.split      = survivor.split;
                    parent.component  = survivor.component;
                    parent.children   = survivor.children;
                }
                selectedId = null;
                render();
            }

            function clearCanvas() {
                nodeCounter = 0;
                grid = { id: 'root', direction: null, split: 50, children: [], component: null };
                selectedId = null;
                lastCustomerKey = null;
                render();
            }

            // --- Component definitions ---
            const componentDefs = {
                text:      '<path d="M5 5h10M10 5v10M7 15h6" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>',
                image:     '<rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1"/><circle cx="7.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1"/><path d="M2 13.5l4-4 3 3 2.5-2.5 4.5 4.5" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>',
                video:     '<rect x="2" y="5" width="12" height="10" rx="2" stroke="currentColor" stroke-width="1"/><path d="M14 8.5l4-2v5l-4-2V8.5z" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>',
                carousel:  '<rect x="1" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1" opacity="0.4"/><rect x="6" y="3" width="8" height="11" rx="1.5" stroke="currentColor" stroke-width="1"/><rect x="15" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1" opacity="0.4"/><circle cx="8.5" cy="17.5" r="0.75" fill="currentColor"/><circle cx="10" cy="17.5" r="0.75" fill="currentColor"/><circle cx="11.5" cy="17.5" r="0.75" fill="currentColor"/>',
                ticker:    '<rect x="2" y="7" width="16" height="6" rx="1.5" stroke="currentColor" stroke-width="1"/><path d="M5 10h5" stroke="currentColor" stroke-width="1" stroke-linecap="round"/><path d="M13 8.5l2.5 1.5-2.5 1.5" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>',
                clock:     '<circle cx="10" cy="10" r="7.5" stroke="currentColor" stroke-width="1"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>',
                weather:   '<circle cx="10" cy="8.5" r="3" stroke="currentColor" stroke-width="1"/><path d="M10 3v1M10 14v1M3.5 8.5h1M15.5 8.5h1M5.6 4.6l.7.7M13.7 4.6l-.7.7" stroke="currentColor" stroke-width="1" stroke-linecap="round"/><path d="M5 16a3 3 0 010-6h.5a4 4 0 017 0H13a3 3 0 010 6H5z" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>',
                countdown: '<circle cx="10" cy="11" r="7" stroke="currentColor" stroke-width="1"/><path d="M10 8v3l-2.5 2" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 3h4M10 1v2" stroke="currentColor" stroke-width="1" stroke-linecap="round"/>',
                qr:        '<rect x="3" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1"/><rect x="12" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1"/><rect x="3" y="12" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1"/><rect x="4.5" y="4.5" width="2" height="2" fill="currentColor"/><rect x="13.5" y="4.5" width="2" height="2" fill="currentColor"/><rect x="4.5" y="13.5" width="2" height="2" fill="currentColor"/><path d="M12 12h2v2h-2zM14 14h2v2h-2zM12 16h2M16 12v2" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>',
            };

            // --- Customer input builder ---
            const inputClass  = 'w-full rounded-xl border border-white/10 bg-slate-950 px-4 text-sm text-white outline-none transition placeholder:text-slate-500 focus:border-cyan-400/60';
            const labelClass  = 'text-xs font-medium text-slate-400';
            const fieldClass  = 'flex flex-col gap-1.5';
            const selectClass = 'h-11 w-full appearance-none rounded-xl border border-white/10 bg-slate-950 px-4 text-sm text-white outline-none transition focus:border-cyan-400/60';

            function buildCustomerInput(component) {
                const uploadZone = (accept, label) => `
                    <label class="flex flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-slate-950 p-6 cursor-pointer transition hover:border-cyan-400/40">
                        <svg class="size-7 text-slate-500" viewBox="0 0 20 20" fill="none"><path d="M10 4v8M6 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        <span class="text-xs text-slate-400">${label}</span>
                        <input type="file" accept="${accept}" class="hidden">
                    </label>`;

                const map = {
                    text: `
                        <p class="${labelClass}">Text content</p>
                        <div class="rounded-xl border border-white/10 bg-slate-950 overflow-hidden flex flex-col h-[80%]">
                            <div class="flex items-center gap-0.5 border-b border-white/10 px-2 py-1.5 shrink-0">
                                <button class="px-2 py-0.5 rounded text-xs font-bold text-slate-400 hover:bg-white/5 hover:text-white transition">B</button>
                                <button class="px-2 py-0.5 rounded text-xs italic text-slate-400 hover:bg-white/5 hover:text-white transition">I</button>
                                <button class="px-2 py-0.5 rounded text-xs underline text-slate-400 hover:bg-white/5 hover:text-white transition">U</button>
                                <div class="w-px h-4 bg-white/10 mx-1"></div>
                                <button class="px-2 py-0.5 rounded text-xs text-slate-400 hover:bg-white/5 hover:text-white transition">H1</button>
                                <button class="px-2 py-0.5 rounded text-xs text-slate-400 hover:bg-white/5 hover:text-white transition">H2</button>
                                <div class="w-px h-4 bg-white/10 mx-1"></div>
                                <button class="px-2 py-0.5 rounded text-xs text-slate-400 hover:bg-white/5 hover:text-white transition">≡</button>
                                <button class="px-2 py-0.5 rounded text-xs text-slate-400 hover:bg-white/5 hover:text-white transition">⁝≡</button>
                            </div>
                            <div contenteditable="true" class="p-3 text-sm text-slate-300 flex-1 outline-none min-h-[80px]"></div>
                        </div>`,

                    image: `<label class="h-[80%] flex flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-slate-950 cursor-pointer transition hover:border-cyan-400/40 min-h-[120px]">
                        <svg class="size-7 text-slate-500" viewBox="0 0 20 20" fill="none"><path d="M10 4v8M6 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        <span class="text-xs text-slate-400">Click to upload or drag & drop · PNG, JPG, WebP</span>
                        <input type="file" accept="image/*" class="hidden"></label>`,

                    video: `<label class="flex-1 flex flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-slate-950 cursor-pointer transition hover:border-cyan-400/40 min-h-[120px]">
                        <svg class="size-7 text-slate-500" viewBox="0 0 20 20" fill="none"><path d="M10 4v8M6 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        <span class="text-xs text-slate-400">Click to upload or drag & drop · MP4, WebM</span>
                        <input type="file" accept="video/*" class="hidden"></label>`,

                    carousel: `
                        <p class="${labelClass}">Slides</p>
                        <label class="flex-1 flex flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-slate-950 cursor-pointer transition hover:border-cyan-400/40 min-h-[120px]">
                            <svg class="size-7 text-slate-500" viewBox="0 0 20 20" fill="none"><path d="M10 4v8M6 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <span class="text-xs text-slate-400">Add images · PNG, JPG, WebP</span>
                            <span class="text-xs text-slate-600">Each uploaded image becomes a slide.</span>
                            <input type="file" accept="image/*" multiple class="hidden">
                        </label>`,

                    ticker: `
                        <p class="${labelClass}">Scrolling text</p>
                        <textarea placeholder="Breaking news · Type your message here..." class="${inputClass} py-3 resize-none flex-1 min-h-[80px]"></textarea>
                        <div class="${fieldClass} shrink-0">
                            <span class="${labelClass}">Speed</span>
                            <input type="range" min="1" max="10" value="5" class="w-full accent-cyan-400">
                        </div>`,

                    clock: `
                        <div class="${fieldClass}">
                            <span class="${labelClass}">Time zone</span>
                            <select class="${selectClass}">
                                <option>Europe/Amsterdam</option>
                                <option>Europe/London</option>
                                <option>America/New_York</option>
                                <option>America/Los_Angeles</option>
                                <option>Asia/Tokyo</option>
                            </select>
                        </div>
                        <div class="${fieldClass}">
                            <span class="${labelClass}">Format</span>
                            <select class="${selectClass}">
                                <option>HH:mm:ss (24h)</option>
                                <option>HH:mm (24h)</option>
                                <option>hh:mm a (12h)</option>
                            </select>
                        </div>`,

                    weather: `
                        <div class="${fieldClass}">
                            <span class="${labelClass}">Location</span>
                            <div class="relative">
                                <input type="text" placeholder="Amsterdam, Netherlands" class="${inputClass} h-11 pr-10">
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 size-4 text-slate-500" viewBox="0 0 20 20" fill="none"><path d="M10 2a6 6 0 016 6c0 4-6 10-6 10S4 12 4 8a6 6 0 016-6z" stroke="currentColor" stroke-width="1.5"/><circle cx="10" cy="8" r="2" stroke="currentColor" stroke-width="1.5"/></svg>
                            </div>
                        </div>
                        <div class="${fieldClass}">
                            <span class="${labelClass}">Unit</span>
                            <select class="${selectClass}">
                                <option>°C — Celsius</option>
                                <option>°F — Fahrenheit</option>
                            </select>
                        </div>`,

                    countdown: `
                        <div class="${fieldClass}">
                            <span class="${labelClass}">Target date</span>
                            <input type="date" class="${inputClass} h-11">
                        </div>
                        <div class="${fieldClass}">
                            <span class="${labelClass}">Target time</span>
                            <input type="time" class="${inputClass} h-11">
                        </div>
                        <div class="${fieldClass}">
                            <span class="${labelClass}">Label</span>
                            <input type="text" placeholder="Event starts in..." class="${inputClass} h-11">
                        </div>`,

                    qr: `
                        <div class="${fieldClass}">
                            <span class="${labelClass}">URL</span>
                            <input type="url" placeholder="https://example.com" class="${inputClass} h-11">
                        </div>`,
                };

                return map[component] ?? null;
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
                    const center = document.createElement('div');
                    center.style.cssText = 'position:absolute; inset:0; display:flex; align-items:center; justify-content:center; pointer-events:none; z-index:4; user-select:none;';
                    if (node.component && componentDefs[node.component]) {
                        center.innerHTML = `<svg viewBox="0 0 20 20" fill="none" style="width:56px;height:56px;color:rgba(34,211,238,0.5)">${componentDefs[node.component]}</svg>`;
                    } else {
                        center.style.fontSize   = '48px';
                        center.style.fontWeight = '700';
                        center.style.color      = 'rgba(34,211,238,0.35)';
                        center.style.fontFamily = 'monospace';
                        center.textContent = order.get(node.id) ?? '';
                    }
                    el.appendChild(center);

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

                const selectedNode = selectedId ? findNode(grid, selectedId) : null;
                const isCustomer   = viewMode === 'customer';

                const hasSelection = selectedId !== null;
                canvasOverlay.classList.toggle('hidden', !hasSelection || isCustomer);

                if (hasSelection && !isCustomer) {
                    const isLeaf = selectedNode && selectedNode.children.length === 0;
                    btnSliceH.classList.toggle('hidden', !isLeaf);
                    btnSliceV.classList.toggle('hidden', !isLeaf);
                    btnDelete.classList.toggle('hidden', selectedId === 'root');
                    btnDeleteAll.classList.toggle('hidden', selectedId === 'root');
                }

                // Admin: highlight active component button
                document.querySelectorAll('[data-component]').forEach(btn => {
                    const active = selectedNode?.component === btn.dataset.component;
                    btn.classList.toggle('border-cyan-400/60', active);
                    btn.classList.toggle('text-cyan-300', active);
                    btn.classList.toggle('border-white/10', !active);
                    btn.classList.toggle('text-slate-400', !active);
                });

                // Customer: update context input panel only when selection/component changes
                if (isCustomer) {
                    const component   = selectedNode?.children.length === 0 ? selectedNode?.component : null;
                    const customerKey = `${selectedId ?? 'none'}:${component ?? 'none'}`;
                    if (customerKey !== lastCustomerKey) {
                        lastCustomerKey = customerKey;
                        const html = component
                            ? buildCustomerInput(component)
                            : selectedNode
                                ? '<p class="text-sm text-slate-500">No component assigned to this section.</p>'
                                : '<p class="text-sm text-slate-500">Select a section in the layout to edit its content.</p>';
                        customerInput.innerHTML = `<div class="flex flex-col gap-5 h-full">${html}</div>`;
                    }
                } else {
                    lastCustomerKey = null;
                }

                // Panel visibility
                panelAdmin.classList.toggle('hidden', isCustomer);
                panelCustomer.classList.toggle('hidden', !isCustomer);

                // View toggle button states
                for (const [btn, active] of [[btnViewAdmin, !isCustomer], [btnViewCustomer, isCustomer]]) {
                    btn.classList.toggle('border-cyan-400/40',  active);
                    btn.classList.toggle('bg-cyan-500/10',      active);
                    btn.classList.toggle('text-cyan-300',       active);
                    btn.classList.toggle('hover:bg-cyan-500/20',active);
                    btn.classList.toggle('border-white/10',    !active);
                    btn.classList.toggle('bg-slate-950',       !active);
                    btn.classList.toggle('text-slate-400',     !active);
                    btn.classList.toggle('hover:border-white/20', !active);
                    btn.classList.toggle('hover:text-slate-200',  !active);
                }
            }

            // --- Control button events ---
            btnSliceH.addEventListener('click', e => { e.stopPropagation(); if (selectedId) slice(selectedId, 'h'); });
            btnSliceV.addEventListener('click', e => { e.stopPropagation(); if (selectedId) slice(selectedId, 'v'); });
            btnDelete.addEventListener('click', e => { e.stopPropagation(); if (selectedId) deleteNode(selectedId); });
            btnDeleteAll.addEventListener('click', e => { e.stopPropagation(); clearCanvas(); });
            btnSave.addEventListener('click', () => console.log('grid JSON:', JSON.stringify(grid, null, 2)));

            btnViewAdmin.addEventListener('click', () => { viewMode = 'admin'; render(); });
            btnViewCustomer.addEventListener('click', () => { viewMode = 'customer'; render(); });

            document.querySelectorAll('[data-component]').forEach(btn => {
                btn.addEventListener('click', e => {
                    e.stopPropagation();
                    if (!selectedId) return;
                    const node = findNode(grid, selectedId);
                    if (!node || node.children.length > 0) return;
                    node.component = node.component === btn.dataset.component ? null : btn.dataset.component;
                    render();
                });
            });

            // Deselect on outside click (skip in customer mode — inputs must stay active)
            document.addEventListener('click', () => {
                if (viewMode === 'customer') return;
                selectedId = null;
                render();
            });

            // --- Init ---
            orientationSelect.addEventListener('change', e => applyOrientation(e.target.value));
            applyOrientation(orientationSelect.value);
            render();
        })();
    </script>
</body>
</html>
