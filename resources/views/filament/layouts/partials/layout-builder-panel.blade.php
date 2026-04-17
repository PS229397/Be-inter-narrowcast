<aside
    class="lb-panel flex h-full min-h-0 flex-1 flex-col"
    x-on:click.stop
>
    <div
        x-data="{
            activeTab: 'base',
            panelMode: 'components',
            draftCss: '',
            draftJs: '',
            activeCustomizeNodeId: null,
            previewNodeId: null,
            previewCss: null,
            previewIntervalId: null,
            editorVersion: 0,
            persistedCustomCssByNode: {},
            customSearch: '',
            customPage: 1,
            customPageSize: 6,
            customComponentsList: @js($customComponents),
            filteredCustomComponents() {
                const term = this.customSearch.trim().toLowerCase()
                if (!term) return this.customComponentsList

                return this.customComponentsList.filter((component) => {
                    const title = (component.title ?? '').toLowerCase()
                    const customer = (component.customer ?? '').toLowerCase()
                    return title.includes(term) || customer.includes(term)
                })
            },
            customPageCount() {
                return Math.max(1, Math.ceil(this.filteredCustomComponents().length / this.customPageSize))
            },
            paginatedCustomComponents() {
                const start = (this.customPage - 1) * this.customPageSize
                return this.filteredCustomComponents().slice(start, start + this.customPageSize)
            },
            goToCustomPage(page) {
                const max = this.customPageCount()
                this.customPage = Math.min(max, Math.max(1, page))
            },
            resetCustomPaging() {
                this.customPage = 1
            },
            selectedLeafNodeIdFromDom() {
                return document.querySelector('.lb-grid .lb-node--leaf.is-selected')?.dataset?.nodeId ?? null
            },
            defaultCssTemplate() {
                return [
                    'background-color: transparent;',
                    'background-image: none;',
                    'background-size: auto;',
                    'background-position: 0% 0%;',
                    'background-repeat: repeat;',
                ].join('\n')
            },
            toEditorCss(declarations) {
                const raw = (declarations ?? '').trim()
                const base = raw || this.defaultCssTemplate()

                if (base.includes('{')) {
                    return base
                }

                const body = base
                    .split('\n')
                    .map((line) => line.trim())
                    .filter((line) => line.length > 0)
                    .map((line) => '  ' + line)
                    .join('\n')

                return `.section {\n${body}\n}`
            },
            fromEditorCss(value) {
                const raw = (value ?? '').trim()
                if (!raw) return null

                const start = raw.indexOf('{')
                const end = raw.lastIndexOf('}')

                if (start === -1 || end === -1 || end <= start) {
                    return raw
                }

                const inner = raw.slice(start + 1, end)
                    .split('\n')
                    .map((line) => line.trim())
                    .filter((line) => line.length > 0)
                    .join('\n')

                return inner || null
            },
            editorElementId(refName) {
                if (refName === 'cssEditor') {
                    return 'lb-css-editor-' + this.editorVersion
                }

                return 'lb-js-editor-' + this.editorVersion
            },
            resolveEditorRef(refName) {
                const editorById = document.getElementById(this.editorElementId(refName))
                if (editorById) {
                    return editorById
                }

                const ref = this.$refs[refName]

                if (Array.isArray(ref)) {
                    return ref[ref.length - 1] ?? null
                }

                if (ref) {
                    return ref
                }
                return null
            },
            readMonacoDomValue(ref) {
                if (!ref) return null

                const lines = ref.querySelectorAll('.view-lines .view-line')
                if (!lines || lines.length === 0) {
                    return null
                }

                const value = Array.from(lines)
                    .map((line) => (line.textContent ?? '').replace(/\u00a0/g, ''))
                    .join('\n')
                    .trimEnd()

                return value === '' ? '' : value
            },
            getEditorState(refName, fallback = '') {
                const ref = this.resolveEditorRef(refName)
                if (!ref) return fallback

                const domValue = this.readMonacoDomValue(ref)
                if (domValue !== null) {
                    return domValue
                }

                const editor = Alpine.$data(ref)
                if (!editor) return fallback

                return editor.state ?? fallback
            },
            syncEditorState(refName, value) {
                const ref = this.resolveEditorRef(refName)
                if (!ref) return

                const editor = Alpine.$data(ref)
                if (!editor) return

                editor.shouldUpdateState = true
                editor.state = value
            },
            syncEditorStates() {
                this.syncEditorState('cssEditor', this.draftCss)
                this.syncEditorState('jsEditor', this.draftJs)
            },
            syncEditorStatesLater() {
                this.$nextTick(() => {
                    this.syncEditorStates()
                    window.setTimeout(() => this.syncEditorStates(), 100)
                    window.setTimeout(() => this.syncEditorStates(), 260)
                })
            },
            disableCssDiagnostics() {
                if (!window.monaco?.languages?.css?.cssDefaults) return

                window.monaco.languages.css.cssDefaults.setDiagnosticsOptions({
                    validate: false,
                })
            },
            openCustomize() {
                if (!this.isLeafSelected()) return

                this.activeCustomizeNodeId = this.selectedLeafNodeIdFromDom()
                this.panelMode = 'customize'
                this.disableCssDiagnostics()
                this.startPreviewLoop()
                this.$nextTick(() => {
                    window.dispatchEvent(new CustomEvent('lb-open-customize'))
                    window.dispatchEvent(new Event('resize'))
                })
                this.syncEditorStatesLater()
            },
            saveCustomize() {
                const nodeId = this.activeCustomizeNodeId ?? this.selectedLeafNodeIdFromDom()
                if (!nodeId) return

                const cssState = this.getEditorState('cssEditor', this.draftCss)
                const jsState = this.getEditorState('jsEditor', this.draftJs)
                this.draftCss = cssState ?? this.draftCss
                this.draftJs = jsState ?? this.draftJs
                const jsValue = (jsState ?? '').trim()

                this.stopPreviewLoop()
                this.clearPreview()
                window.dispatchEvent(new CustomEvent('lb-save-customize', {
                    detail: {
                        nodeId,
                        css: this.fromEditorCss(cssState),
                        js: jsValue === '' || jsValue === '// Customize this section' ? null : jsState,
                    },
                }))

                this.panelMode = 'components'
            },
            deleteCustomize() {
                const nodeId = this.activeCustomizeNodeId ?? this.selectedLeafNodeIdFromDom()
                if (!nodeId) return

                this.stopPreviewLoop()
                this.clearPreview()
                window.dispatchEvent(new CustomEvent('lb-save-customize', {
                    detail: { nodeId, css: null, js: null },
                }))

                this.draftCss = this.toEditorCss(this.defaultCssTemplate())
                this.draftJs = '// Customize this section'
                this.syncEditorStatesLater()
                this.$nextTick(() => this.flushPreview())
            },
            cancelCustomize() {
                const nodeId = this.activeCustomizeNodeId ?? this.selectedLeafNodeIdFromDom()
                this.stopPreviewLoop()

                if (nodeId) {
                    const persistedCssRaw = this.persistedCustomCssByNode[nodeId] ?? null
                    const persistedCss = this.fromEditorCss(persistedCssRaw)
                    const previewEl = document.querySelector('.lb-grid [data-node-id=' + nodeId + ']')

                    if (previewEl) {
                        if (persistedCss && persistedCss.trim() !== '') {
                            previewEl.setAttribute('style', persistedCss)
                        } else {
                            previewEl.removeAttribute('style')
                        }
                    }
                }

                if (nodeId) {
                    window.dispatchEvent(new CustomEvent('lb-preview-customize', {
                        detail: { nodeId, css: null },
                    }))
                } else {
                    this.clearPreview()
                }

                this.previewNodeId = null
                this.previewCss = null
                this.panelMode = 'components'
            },
            clearPreview() {
                window.dispatchEvent(new CustomEvent('lb-preview-customize', {
                    detail: { nodeId: null, css: null },
                }))
                this.previewNodeId = null
                this.previewCss = null
            },
            flushPreview() {
                if (this.panelMode !== 'customize') return

                const nodeId = this.activeCustomizeNodeId ?? this.selectedLeafNodeIdFromDom()
                if (!nodeId) return

                const cssState = this.getEditorState('cssEditor', this.draftCss)
                const cssDecl = this.fromEditorCss(cssState)
                this.draftCss = cssState ?? this.draftCss
                this.previewNodeId = nodeId
                this.previewCss = cssDecl

                const previewEl = document.querySelector('.lb-grid [data-node-id=' + nodeId + ']')
                if (previewEl) {
                    if (cssDecl && cssDecl.trim() !== '') {
                        previewEl.setAttribute('style', cssDecl)
                    } else {
                        previewEl.removeAttribute('style')
                    }
                }

                window.dispatchEvent(new CustomEvent('lb-preview-customize', {
                    detail: { nodeId, css: cssDecl },
                }))
            },
            startPreviewLoop() {
                this.stopPreviewLoop()
                this.flushPreview()
                this.previewIntervalId = window.setInterval(() => this.flushPreview(), 90)
            },
            stopPreviewLoop() {
                if (this.previewIntervalId !== null) {
                    window.clearInterval(this.previewIntervalId)
                    this.previewIntervalId = null
                }
            },
        }"
        x-on:lb-load-customize.window="
            const cssFromEvent = ($event.detail.css ?? '').trim()
            const jsFromEvent = ($event.detail.js ?? '').trim()
            const hasCustomCss = !!$event.detail.hasCustomCss
            activeCustomizeNodeId = $event.detail.nodeId ?? null
            draftCss = toEditorCss(cssFromEvent || defaultCssTemplate())
            draftJs = jsFromEvent || '// Customize this section'
            if (activeCustomizeNodeId) {
                persistedCustomCssByNode[activeCustomizeNodeId] = hasCustomCss ? cssFromEvent : null
            }
            previewNodeId = null
            previewCss = null
            editorVersion++
            syncEditorStatesLater()
            if (panelMode === 'customize') {
                $nextTick(() => {
                    disableCssDiagnostics()
                    flushPreview()
                    window.dispatchEvent(new Event('resize'))
                })
            }
        "
        class="lb-panel-scroll flex h-full min-h-0 flex-1 flex-col gap-4 overflow-visible"
    >
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
                    x-on:click="cancelCustomize()"
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
                    <div class="flex h-full min-h-0 flex-col gap-3">
                        <div>
                            <input
                                type="search"
                                x-model="customSearch"
                                x-on:input="resetCustomPaging()"
                                placeholder="Search custom components..."
                                class="w-full rounded-[0.85rem] border border-[var(--lb-border)] bg-[var(--lb-surface-muted)] px-3 py-2 text-sm text-[var(--lb-text)] placeholder:text-[var(--lb-text-soft)] focus:border-[var(--lb-accent-border)] focus:outline-none"
                            />
                        </div>

                        <div class="lb-component-stack lb-component-stack--flush grid gap-2 p-0">
                            <template x-for="component in paginatedCustomComponents()" :key="component.key">
                                <x-layout-builder.component-row
                                    x-bind:data-component="component.key"
                                    x-on:click.stop="assignComponent(component.key)"
                                    x-bind:class="{ 'is-active': selectedComponentKey() === component.key }"
                                >
                                    <svg class="lb-row-icon h-6 w-6" viewBox="0 0 20 20" fill="none" aria-hidden="true" x-html="component.icon"></svg>
                                    <div class="lb-row-copy min-w-0">
                                        <div class="lb-row-title overflow-hidden text-ellipsis whitespace-nowrap text-sm font-semibold text-inherit" x-text="component.title"></div>
                                        <div
                                            x-show="component.customer"
                                            class="lb-row-meta mt-[0.15rem] overflow-hidden text-ellipsis whitespace-nowrap text-xs text-[var(--lb-text-soft)]"
                                            x-text="component.customer"
                                        ></div>
                                    </div>
                                </x-layout-builder.component-row>
                            </template>
                        </div>

                        <div x-show="filteredCustomComponents().length === 0">
                            <x-layout-builder.note dashed class="lb-empty-note">
                                No custom components match your search.
                            </x-layout-builder.note>
                        </div>

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
                            <template x-for="instance in [editorVersion]" :key="'css-' + instance + '-' + (activeCustomizeNodeId || 'none')">
                                <div
                                    x-load
                                    x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('monaco-editor-css', package: 'timo-de-winter/filament-monaco-editor'))]"
                                    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('monaco-editor', package: 'timo-de-winter/filament-monaco-editor') }}"
                                    x-ref="cssEditor"
                                    x-bind:id="editorElementId('cssEditor')"
                                    x-data="monacoEditor({
                                        key: 'layout-builder-custom-css-' + (activeCustomizeNodeId || 'none') + '-' + instance,
                                        isLiveDebounced: false,
                                        isLiveOnBlur: false,
                                        liveDebounce: null,
                                        state: draftCss || toEditorCss(defaultCssTemplate()),
                                        language: 'css',
                                    })"
                                    wire:ignore
                                    class="h-full min-h-0"
                                >
                                    <div id="monaco-editor" class="h-full"></div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex min-h-0 flex-1 flex-col gap-2">
                        <label class="text-xs font-semibold uppercase tracking-wide text-[var(--lb-text-muted)]">Custom JS (Monaco)</label>
                        <div
                            data-lb-editor="js"
                            class="min-h-[12rem] flex-1 overflow-hidden rounded-[0.75rem] border border-[var(--lb-border-soft)] bg-[var(--lb-surface-muted)]"
                        >
                            <template x-for="instance in [editorVersion]" :key="'js-' + instance + '-' + (activeCustomizeNodeId || 'none')">
                                <div
                                    x-load
                                    x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('monaco-editor-css', package: 'timo-de-winter/filament-monaco-editor'))]"
                                    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('monaco-editor', package: 'timo-de-winter/filament-monaco-editor') }}"
                                    x-ref="jsEditor"
                                    x-bind:id="editorElementId('jsEditor')"
                                    x-data="monacoEditor({
                                        key: 'layout-builder-custom-js-' + (activeCustomizeNodeId || 'none') + '-' + instance,
                                        isLiveDebounced: false,
                                        isLiveOnBlur: false,
                                        liveDebounce: null,
                                        state: draftJs || '// Customize this section',
                                        language: 'javascript',
                                    })"
                                    wire:ignore
                                    class="h-full min-h-0"
                                >
                                    <div id="monaco-editor" class="h-full"></div>
                                </div>
                            </template>
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
                class="p-3"
            >
                <div
                    x-show="activeTab === 'custom'"
                    class="flex items-center justify-between gap-2"
                >
                    <button
                        type="button"
                        x-on:click="goToCustomPage(customPage - 1)"
                        x-bind:disabled="customPage <= 1"
                        class="lb-tab-button h-8 w-8 rounded-full px-0 py-0 text-xs disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        &lt;
                    </button>
                    <div class="text-xs font-medium text-[var(--lb-text-soft)]">
                        <span x-text="customPage"></span>
                        /
                        <span x-text="customPageCount()"></span>
                    </div>
                    <button
                        type="button"
                        x-on:click="goToCustomPage(customPage + 1)"
                        x-bind:disabled="customPage >= customPageCount()"
                        class="lb-tab-button h-8 w-8 rounded-full px-0 py-0 text-xs disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        &gt;
                    </button>
                </div>
                <div
                    x-show="activeTab === 'custom'"
                    class="border-t border-[var(--lb-border-soft)]"
                ></div>
                <div
                    x-show="activeTab === 'custom'"
                    style="height: 15px;"
                ></div>
                <x-layout-builder.tab-button
                    x-on:click="openCustomize()"
                    x-bind:disabled="!isLeafSelected()"
                    x-bind:class="{ 'opacity-50 cursor-not-allowed': !isLeafSelected() }"
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
                <x-layout-builder.tab-button x-on:click="deleteCustomize()" class="w-full">
                    Delete code
                </x-layout-builder.tab-button>
                <x-layout-builder.tab-button x-on:click="saveCustomize()" class="w-full">
                    Save
                </x-layout-builder.tab-button>
            </div>

        </x-layout-builder.card>
    </div>
</aside>
