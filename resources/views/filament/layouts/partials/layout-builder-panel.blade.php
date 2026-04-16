<aside
    class="lb-panel flex h-full min-h-0 flex-1 flex-col"
    x-on:click.stop
>
    <div x-data="{ activeTab: 'base' }" class="lb-panel-scroll flex h-full min-h-0 flex-1 flex-col gap-4 overflow-visible">
        <x-layout-builder.card subtle class="lb-panel-card flex h-full min-h-0 flex-1 flex-col overflow-hidden">
            <div class="lb-tab-list grid grid-cols-2 gap-2 border-b border-[var(--lb-border-soft)] p-4" role="tablist" aria-label="Layout component tabs">
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

            <x-layout-builder.pane x-cloak x-show="activeTab === 'base'">
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

            <x-layout-builder.pane x-cloak x-show="activeTab === 'custom'">
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
        </x-layout-builder.card>
    </div>
</aside>
