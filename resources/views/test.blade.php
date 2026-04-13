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
                    class="relative isolate grid w-full cursor-pointer place-items-center rounded-xl border border-dashed border-cyan-400/30 bg-slate-950/70 text-lg font-semibold text-cyan-300 shadow-2xl shadow-cyan-950/20 transition xl:h-[var(--canvas-height)] xl:w-[var(--canvas-width)]"
                >
                    <span id="canvas-label">canvas 940x540</span>

                    <div id="canvas-overlay" class="pointer-events-none absolute inset-0 z-20 hidden">
                        <div class="pointer-events-auto absolute bottom-5 right-5 flex gap-2.5">
                            <button type="button" class="grid size-10 place-items-center rounded-lg border border-cyan-400/40 bg-slate-900/95 text-slate-100 shadow-lg shadow-slate-950/60 transition hover:border-cyan-300 hover:text-cyan-300">
                                <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M3 10H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 3" />
                                </svg>
                            </button>
                            <button type="button" class="grid size-10 place-items-center rounded-lg border border-cyan-400/40 bg-slate-900/95 text-slate-100 shadow-lg shadow-slate-950/60 transition hover:border-cyan-300 hover:text-cyan-300">
                                <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M10 3V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 3" />
                                </svg>
                            </button>
                            <button type="button" class="grid size-10 place-items-center rounded-lg border border-cyan-400/40 bg-slate-900/95 text-slate-100 shadow-lg shadow-slate-950/60 transition hover:border-cyan-300 hover:text-cyan-300">
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
                            <button type="button" class="h-11 flex-1 rounded-xl border border-cyan-400/40 bg-cyan-500/10 text-sm font-medium text-cyan-300 transition hover:border-cyan-300 hover:bg-cyan-500/20">Save</button>
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
            const preview = document.getElementById('layout-preview');
            const orientationSelect = document.getElementById('orientation-select');
            const canvasStage = document.getElementById('canvas-stage');
            const canvasLabel = document.getElementById('canvas-label');
            const canvasOverlay = document.getElementById('canvas-overlay');

            const sizes = {
                landscape: { width: 940, height: 540 },
                portrait: { width: 540, height: 940 },
            };

            let isCanvasSelected = false;

            const applyCanvasSelection = () => {
                canvasOverlay.classList.toggle('hidden', !isCanvasSelected);
                canvasStage.classList.toggle('border-cyan-300', isCanvasSelected);
                canvasStage.classList.toggle('ring-2', isCanvasSelected);
                canvasStage.classList.toggle('ring-cyan-400/50', isCanvasSelected);
            };

            preview.style.setProperty('--preview-height', '1000px');
            preview.style.setProperty('--section-1-width', '1000px');
            preview.style.setProperty('--section-2-width', '420px');

            const applyOrientation = (orientation) => {
                const size = sizes[orientation] ?? sizes.landscape;

                preview.style.setProperty('--canvas-width', `${size.width}px`);
                preview.style.setProperty('--canvas-height', `${size.height}px`);

                canvasLabel.textContent = `canvas ${size.width}x${size.height}`;
            };

            orientationSelect.addEventListener('change', (event) => {
                applyOrientation(event.target.value);
            });

            canvasStage.addEventListener('click', (event) => {
                event.stopPropagation();
                isCanvasSelected = true;
                applyCanvasSelection();
            });

            document.addEventListener('click', () => {
                isCanvasSelected = false;
                applyCanvasSelection();
            });

            applyOrientation(orientationSelect.value);
            applyCanvasSelection();
        })();
    </script>
</body>
</html>
