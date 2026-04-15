@php
    $statePath = $getStatePath();
    $orientation = $getOrientation() ?? 'landscape';
    $baseComponents = $getBaseComponents();
    $customComponents = $getCustomComponents();
    $isStandalone = $isStandalone();
    $catalogKey = md5(json_encode([
        'base' => $baseComponents,
        'custom' => $customComponents,
        'standalone' => $isStandalone,
    ]));
    $builderExtraAttrs = $getExtraAttributeBag();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @if ($isStandalone)
        @php
            $titleStatePath = $getTitleStatePath();
            $orientationStatePath = $getOrientationStatePath();
            $customersStatePath = $getCustomersStatePath();
            $submitAction = $getSubmitAction();
            $submitFormId = $getSubmitFormId();
            $cancelUrl = $getCancelUrl();
            $isEditing = $isEditing();
            $customerOptions = $getCustomerOptions();
            $customerOptionsJs = collect($customerOptions)->mapWithKeys(fn ($name, $id) => [(string) $id => $name])->all();
        @endphp

        <div
            x-load
            x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('layout-builder', package: 'app'))]"
            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('layout-builder', package: 'app') }}"
            x-data="layoutBuilder({
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false) }},
                orientation: @js($orientation),
                emptyGrid: @js(\App\Support\Layouts\LayoutGrid::empty()),
                baseComponents: @js($baseComponents),
                customComponents: @js($customComponents),
                standalone: true,
                pageTitle: $wire.{{ $applyStateBindingModifiers("\$entangle('{$titleStatePath}')", isOptimisticallyLive: false) }},
                isEditing: @js($isEditing),
            })"
            x-init="init()"
            x-on:layout-builder-orientation.window="orientation = $event.detail.value; $nextTick(() => render())"
            wire:key="layout-builder-{{ md5($statePath) }}-{{ $orientation }}-{{ $catalogKey }}"
            class="lb-builder is-standalone mx-auto flex flex-col gap-3"
            {{ $builderExtraAttrs }}
        >
            <h1
                class="text-3xl font-bold text-white"
                x-text="(isEditing ? 'Edit ' : 'Create ') + (pageTitle || 'Untitled layout')"
            ></h1>

            <div class="lb-standalone-layout">
                @include('filament.layouts.partials.layout-builder-canvas')

                <div class="lb-standalone-sidebar">
                    <div class="shrink-0 rounded-xl border border-white/8 bg-[#1c1c21] p-4 shadow-xl sm:p-5">
                        <div class="grid gap-5">
                            <div class="grid gap-[10px] sm:grid-cols-2">
                                <label class="grid gap-2">
                                    <span class="text-sm font-medium text-zinc-200">Title</span>
                                    <input
                                        type="text"
                                        wire:model.live="{{ $titleStatePath }}"
                                        placeholder="Untitled layout"
                                        class="h-11 w-full rounded-xl border border-white/10 bg-[#111114] px-4 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400/60"
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

                            <div class="grid gap-3">
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
                                    <span class="text-sm font-medium text-zinc-200">Customers</span>
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

                                <div class="flex gap-3">
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
                                        <span wire:loading wire:target="{{ $submitAction }}">{{ $isEditing ? 'Saving...' : 'Creating...' }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('filament.layouts.partials.layout-builder-panel')
                </div>
            </div>
        </div>
    @else
        <div
            x-load
            x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('layout-builder', package: 'app'))]"
            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('layout-builder', package: 'app') }}"
            x-data="layoutBuilder({
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false) }},
                orientation: @js($orientation),
                emptyGrid: @js(\App\Support\Layouts\LayoutGrid::empty()),
                baseComponents: @js($baseComponents),
                customComponents: @js($customComponents),
            })"
            x-init="init()"
            wire:ignore
            wire:key="layout-builder-{{ md5($statePath) }}-{{ $orientation }}-{{ $catalogKey }}"
            class="lb-builder"
            {{ $builderExtraAttrs }}
        >
            <div class="lb-workspace">
                @include('filament.layouts.partials.layout-builder-canvas')
                @include('filament.layouts.partials.layout-builder-panel')
            </div>
        </div>
    @endif
</x-dynamic-component>
