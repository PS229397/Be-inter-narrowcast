<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite Tailwind Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-50">
    <main class="mx-auto grid min-h-screen w-full grid-cols-1 gap-[20px] px-4 py-8 lg:py-16 xl:w-[1440px] xl:max-w-[1440px] xl:grid-cols-[980px_440px] xl:px-0 xl:items-center xl:justify-center">
        <section class="w-full rounded-xl border border-white/10 bg-slate-900 shadow-2xl shadow-cyan-950/40 xl:h-[580px] xl:w-[980px]">
            <div class="grid h-full place-items-center p-4 sm:p-5">
                <div class="grid w-full max-w-[940px] place-items-center rounded-xl border border-dashed border-cyan-400/30 bg-slate-950/70 text-lg font-semibold text-cyan-300 shadow-2xl shadow-cyan-950/20 aspect-[94/54] xl:h-[540px] xl:w-[940px]">
                    canvas 940x540
                </div>
            </div>
        </section>

        <section class="grid w-full gap-5 xl:h-[580px] xl:w-[440px] xl:grid-rows-[230px_330px]">
            <div class="rounded-xl border border-white/10 bg-slate-900 p-4 shadow-2xl shadow-cyan-950/40 sm:p-5 xl:h-[230px] xl:w-[440px] xl:p-5">
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
                                <select class="h-11 w-full appearance-none rounded-xl border border-white/10 bg-slate-950 px-4 pr-11 text-sm text-white outline-none transition focus:border-cyan-400/60">
                                    <option selected>Landscape</option>
                                    <option>Portrait</option>
                                </select>
                                <svg class="pointer-events-none absolute right-4 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </label>
                    </div>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-slate-200">Customer</span>
                        <div class="relative">
                            <select class="h-11 w-full appearance-none rounded-xl border border-white/10 bg-slate-950 px-4 pr-11 text-sm text-white outline-none transition focus:border-cyan-400/60">
                                <option selected>Be-interactive</option>
                                <option>All customers</option>
                            </select>
                            <svg class="pointer-events-none absolute right-4 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </label>

                    <p class="self-end text-xs text-slate-400">
                        Leave customer empty to make the layout available to all customers.
                    </p>
                </div>
            </div>
            <div class="rounded-xl border border-white/10 bg-slate-900 p-6 shadow-2xl shadow-cyan-950/40 sm:p-8 xl:h-[330px] xl:w-[440px] xl:p-10">
                <p>component select</p>
            </div>
        </section>
    </main>
</body>
</html>
