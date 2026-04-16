<aside
    class="lb-panel flex h-full min-h-0 flex-1 flex-col"
    x-on:click.stop
>
    <div x-data="{ activeTab: 'base' }" class="lb-panel-scroll flex h-full min-h-0 flex-1 flex-col gap-4 overflow-visible">
        <section class="lb-card lb-panel-card flex h-full min-h-0 flex-1 flex-col overflow-hidden rounded-2xl border border-[var(--lb-border)] bg-[var(--lb-surface-subtle)] text-[var(--lb-text)] shadow-[var(--lb-shadow)] transition-[border-color,background-color,box-shadow,color]">
            <div class="lb-tab-list grid grid-cols-2 gap-2 border-b border-[var(--lb-border-soft)] p-4" role="tablist" aria-label="Layout component tabs">
                <button
                    type="button"
                    x-on:click="activeTab = 'base'"
                    x-bind:class="{ 'is-active': activeTab === 'base' }"
                    class="lb-tab-button inline-flex min-w-0 items-center justify-center gap-2 rounded-[0.85rem] border border-[var(--lb-border)] bg-[var(--lb-surface-muted)] px-[0.95rem] py-[0.8rem] text-[0.8125rem] font-semibold text-[var(--lb-text-muted)] transition-[border-color,background-color,color]"
                >
                    <span>Base components</span>
                </button>
                <button
                    type="button"
                    x-on:click="activeTab = 'custom'"
                    x-bind:class="{ 'is-active': activeTab === 'custom' }"
                    class="lb-tab-button inline-flex min-w-0 items-center justify-center gap-2 rounded-[0.85rem] border border-[var(--lb-border)] bg-[var(--lb-surface-muted)] px-[0.95rem] py-[0.8rem] text-[0.8125rem] font-semibold text-[var(--lb-text-muted)] transition-[border-color,background-color,color]"
                >
                    <span>Custom components</span>
                    <span class="lb-tab-count inline-flex min-w-[1.4rem] items-center justify-center rounded-full border border-[var(--lb-border-soft)] bg-[var(--lb-surface)] px-[0.4rem] py-[0.15rem] text-[0.6875rem] leading-none text-[var(--lb-text-soft)]">{{ count($customComponents) }}</span>
                </button>
            </div>

            <div x-cloak x-show="activeTab === 'base'" class="lb-card-body lb-card-body--fill min-h-0 flex-1 overflow-hidden rounded-b-[inherit] border-t-0 p-0">
                <div class="lb-tab-pane grid h-full min-h-0 content-start gap-3.5 overflow-y-auto p-4">
                    <div class="lb-component-grid grid grid-cols-3 gap-2">
                        @foreach ($baseComponents as $component)
                            <button
                                data-component="{{ $component['key'] }}"
                                type="button"
                                x-on:click.stop="assignComponent(@js($component['key']))"
                                x-bind:class="{ 'is-active': selectedComponentKey() === @js($component['key']) }"
                                class="lb-component-button flex aspect-square flex-col items-center justify-center gap-1.5 rounded-[0.9rem] border border-[var(--lb-border)] bg-[var(--lb-surface-muted)] px-2 py-3 text-[var(--lb-text-muted)] transition-[border-color,color,background-color]"
                            >
                                <svg class="lb-component-icon h-6 w-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">{!! $component['icon'] !!}</svg>
                                <span class="lb-component-label text-center text-xs">{{ $component['label'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    <p class="lb-muted-note rounded-[0.9rem] border border-[var(--lb-border-soft)] bg-[var(--lb-surface)] px-[0.875rem] py-3 text-xs leading-[1.4] text-[var(--lb-text-muted)]">
                        Select a section on the canvas, then choose a base component.
                    </p>
                </div>
            </div>

            <div x-cloak x-show="activeTab === 'custom'" class="lb-card-body lb-card-body--fill min-h-0 flex-1 overflow-hidden rounded-b-[inherit] border-t-0 p-0">
                <div class="lb-tab-pane grid h-full min-h-0 content-start gap-3.5 overflow-y-auto p-4">
                    @if (count($customComponents))
                        <div class="lb-component-stack lb-component-stack--flush grid gap-2 p-0">
                        @foreach ($customComponents as $component)
                            <button
                                data-component="{{ $component['key'] }}"
                                type="button"
                                x-on:click.stop="assignComponent(@js($component['key']))"
                                x-bind:class="{ 'is-active': selectedComponentKey() === @js($component['key']) }"
                                class="lb-component-row flex w-full items-center gap-3 rounded-[0.9rem] border border-[var(--lb-border)] bg-[var(--lb-surface-muted)] px-[0.9rem] py-[0.85rem] text-left text-[var(--lb-text-muted)] transition-[border-color,color,background-color]"
                            >
                                <svg class="lb-row-icon h-6 w-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">{!! $component['icon'] !!}</svg>
                                <div class="lb-row-copy min-w-0">
                                    <div class="lb-row-title overflow-hidden text-ellipsis whitespace-nowrap text-sm font-semibold text-inherit">{{ $component['title'] }}</div>
                                    @if (filled($component['customer'] ?? null))
                                        <div class="lb-row-meta mt-[0.15rem] overflow-hidden text-ellipsis whitespace-nowrap text-xs text-[var(--lb-text-soft)]">{{ $component['customer'] }}</div>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                        </div>
                    @else
                        <p class="lb-empty-note rounded-[0.9rem] border border-dashed border-[var(--lb-border-soft)] bg-[var(--lb-surface)] px-[0.875rem] py-3 text-xs leading-[1.4] text-[var(--lb-text-muted)]">
                            No custom components are available for the current customer selection.
                        </p>
                    @endif
                </div>
            </div>
        </section>
    </div>
</aside>
