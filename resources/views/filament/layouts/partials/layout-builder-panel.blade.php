<aside
    class="lb-panel flex h-full min-h-0 flex-1 flex-col"
    x-on:click.stop
>
    <div x-data="{ activeTab: 'base', panelMode: 'components' }" x-effect="if (panelMode === 'customize') { $nextTick(() => window.dispatchEvent(new Event('resize'))) }" class="lb-panel-scroll flex h-full min-h-0 flex-1 flex-col gap-4 overflow-visible">
        <x-layout-builder.card subtle class="lb-panel-card flex h-full min-h-0 flex-1 flex-col overflow-hidden">

            {{-- ── Components mode: tab bar ─────────────────────────── --}}
            <div
                x-cloak
                x-show="panelMode === 'components'"
                class="lb-tab-list grid grid-cols-2 gap-2 border-b border-[var(--lb-border-soft)] p-4"
                role="tablist"
                aria-label="Layout component tabs"
            >
                <x-layout-builder.tab-button
                    x-on:click="activeTab = 'base'"
                    x-bind:class="{ 'is-active': activeTab === 'base' }"
                >
                    <span>Base components</span>
                </x-layout-builder.tab-button>
                <x-layout-builder.tab-button
                    x-on:click="activeTab = 'custom'"
                    x-bind:class="{ 'is-active': activeTab === 'custom' }"
                >
                    <span>Custom components</span>
                    <span class="lb-tab-count inline-flex min-w-[1.4rem] items-center justify-center rounded-full border border-[var(--lb-border-soft)] bg-[var(--lb-surface)] px-[0.4rem] py-[0.15rem] text-[0.6875rem] leading-none text-[var(--lb-text-soft)]">{{ count($customComponents) }}</span>
                </x-layout-builder.tab-button>
            </div>

            {{-- ── Customize mode: back header ──────────────────────── --}}
            <div
                x-cloak
                x-show="panelMode === 'customize'"
                class="flex items-center gap-3 border-b border-[var(--lb-border-soft)] px-4 py-3"
            >
                <button
                    type="button"
                    x-on:click="panelMode = 'components'"
                    class="lb-tab-button h-[2.45rem] w-[2.45rem] shrink-0 rounded-full px-0 py-0"
                >
                    <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none">
                        <path d="M10 3L5 8L10 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <span class="text-sm font-semibold text-[var(--lb-text)]">Customize section</span>
            </div>

            {{-- ── Components mode: base tab pane ──────────────────── --}}
            <x-layout-builder.pane x-cloak x-show="panelMode === 'components' && activeTab === 'base'">
                <div class="lb-component-grid grid grid-cols-3 gap-2">
                    @foreach ($baseComponents as $baseComponent)
                        @php($baseComponentKeyJs = \Illuminate\Support\Js::from($baseComponent['key']))
                        <x-layout-builder.component-tile
                            data-component="{{ $baseComponent['key'] }}"
                            x-on:click.stop="assignComponent({{ $baseComponentKeyJs }})"
                            x-bind:class="{ 'is-active': selectedComponentKey() === {{ $baseComponentKeyJs }} }"
                        >
                            <svg class="lb-component-icon h-6 w-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">{!! $baseComponent['icon'] !!}</svg>
                            <span class="lb-component-label text-center text-xs">{{ $baseComponent['label'] }}</span>
                        </x-layout-builder.component-tile>
                    @endforeach
                </div>

                <x-layout-builder.note class="lb-muted-note">
                    Select a section on the canvas, then choose a base component.
                </x-layout-builder.note>
            </x-layout-builder.pane>

            {{-- ── Components mode: custom tab pane ────────────────── --}}
            <x-layout-builder.pane x-cloak x-show="panelMode === 'components' && activeTab === 'custom'">
                @if (count($customComponents))
                    <div class="lb-component-stack lb-component-stack--flush grid gap-2 p-0">
                        @foreach ($customComponents as $customComponent)
                            @php($customComponentKeyJs = \Illuminate\Support\Js::from($customComponent['key']))
                            <x-layout-builder.component-row
                                data-component="{{ $customComponent['key'] }}"
                                x-on:click.stop="assignComponent({{ $customComponentKeyJs }})"
                                x-bind:class="{ 'is-active': selectedComponentKey() === {{ $customComponentKeyJs }} }"
                            >
                                <svg class="lb-row-icon h-6 w-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">{!! $customComponent['icon'] !!}</svg>
                                <div class="lb-row-copy min-w-0">
                                    <div class="lb-row-title overflow-hidden text-ellipsis whitespace-nowrap text-sm font-semibold text-inherit">{{ $customComponent['title'] }}</div>
                                    @if (filled($customComponent['customer'] ?? null))
                                        <div class="lb-row-meta mt-[0.15rem] overflow-hidden text-ellipsis whitespace-nowrap text-xs text-[var(--lb-text-soft)]">{{ $customComponent['customer'] }}</div>
                                    @endif
                                </div>
                            </x-layout-builder.component-row>
                        @endforeach
                    </div>
                @else
                    <x-layout-builder.note dashed class="lb-empty-note">
                        No custom components are available for the current customer selection.
                    </x-layout-builder.note>
                @endif
            </x-layout-builder.pane>

            {{-- ── Customize mode: editor pane ─────────────────────── --}}
            <div
                x-cloak
                x-show="panelMode === 'customize'"
                class="flex min-h-0 flex-1 flex-col p-4"
            >
                <div class="flex min-h-0 flex-1 flex-col gap-5">
                    <div class="flex min-h-0 flex-1 flex-col gap-2">
                        <label class="text-xs font-semibold uppercase tracking-wide text-[var(--lb-text-muted)]">Custom CSS (Monaco)</label>
                        <div
                            data-lb-editor="css"
                            class="min-h-[12rem] flex-1 overflow-hidden rounded-[0.75rem] border border-[var(--lb-border-soft)] bg-[var(--lb-surface-muted)]"
                        >
                            <div
                                x-load
                                x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('monaco-editor-css', package: 'timo-de-winter/filament-monaco-editor'))]"
                                x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('monaco-editor', package: 'timo-de-winter/filament-monaco-editor') }}"
                                x-data="monacoEditor({
                                    key: 'layout-builder-custom-css',
                                    isLiveDebounced: false,
                                    isLiveOnBlur: false,
                                    liveDebounce: null,
                                    state: '',
                                    language: 'css',
                                })"
                                wire:ignore
                                class="h-full min-h-0"
                            >
                                <div id="monaco-editor" class="h-full"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex min-h-0 flex-1 flex-col gap-2">
                        <label class="text-xs font-semibold uppercase tracking-wide text-[var(--lb-text-muted)]">Custom JS (Monaco)</label>
                        <div
                            data-lb-editor="js"
                            class="min-h-[12rem] flex-1 overflow-hidden rounded-[0.75rem] border border-[var(--lb-border-soft)] bg-[var(--lb-surface-muted)]"
                        >
                            <div
                                x-load
                                x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('monaco-editor-css', package: 'timo-de-winter/filament-monaco-editor'))]"
                                x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('monaco-editor', package: 'timo-de-winter/filament-monaco-editor') }}"
                                x-data="monacoEditor({
                                    key: 'layout-builder-custom-js',
                                    isLiveDebounced: false,
                                    isLiveOnBlur: false,
                                    liveDebounce: null,
                                    state: '',
                                    language: 'javascript',
                                })"
                                wire:ignore
                                class="h-full min-h-0"
                            >
                                <div id="monaco-editor" class="h-full"></div>
                            </div>
                        </div>
                    </div>

                    <x-layout-builder.note>
                        Select a section on the canvas to customize its styles and scripts.
                    </x-layout-builder.note>
                </div>
            </div>

            {{-- ── Components mode: footer (Customize button) ──────── --}}
            <div
                x-cloak
                x-show="panelMode === 'components'"
                class="border-t border-[var(--lb-border-soft)] p-3"
            >
                <x-layout-builder.tab-button
                    x-on:click="panelMode = 'customize'"
                    class="w-full"
                >
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none">
                        <path d="M7 7L3 10L7 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13 7L17 10L13 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Customize
                </x-layout-builder.tab-button>
            </div>

            {{-- ── Customize mode: footer (Cancel / Save) ──────────── --}}
            <div
                x-cloak
                x-show="panelMode === 'customize'"
                class="flex gap-2 border-t border-[var(--lb-border-soft)] p-3"
            >
                <x-layout-builder.tab-button class="w-full">
                    Save
                </x-layout-builder.tab-button>
            </div>

        </x-layout-builder.card>
    </div>
</aside>
