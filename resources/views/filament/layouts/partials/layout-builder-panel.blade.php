<aside
    class="lb-panel"
    x-on:click.stop
>
    <div x-data="{ activeTab: 'base' }" class="lb-panel-scroll">
        <section class="lb-card lb-panel-card">
            <div class="lb-tab-list" role="tablist" aria-label="Layout component tabs">
                <button
                    type="button"
                    x-on:click="activeTab = 'base'"
                    x-bind:class="{ 'is-active': activeTab === 'base' }"
                    class="lb-tab-button"
                >
                    <span>Base components</span>
                </button>
                <button
                    type="button"
                    x-on:click="activeTab = 'custom'"
                    x-bind:class="{ 'is-active': activeTab === 'custom' }"
                    class="lb-tab-button"
                >
                    <span>Custom components</span>
                    <span class="lb-tab-count">{{ count($customComponents) }}</span>
                </button>
            </div>

            <div x-cloak x-show="activeTab === 'base'" class="lb-card-body lb-card-body--fill">
                <div class="lb-tab-pane">
                    <div class="lb-component-grid">
                        @foreach ($baseComponents as $component)
                            <button
                                data-component="{{ $component['key'] }}"
                                type="button"
                                x-on:click.stop="assignComponent(@js($component['key']))"
                                x-bind:class="{ 'is-active': selectedComponentKey() === @js($component['key']) }"
                                class="lb-component-button"
                            >
                                <svg class="lb-component-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true">{!! $component['icon'] !!}</svg>
                                <span class="lb-component-label">{{ $component['label'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    <p class="lb-muted-note">
                        Select a section on the canvas, then choose a base component.
                    </p>
                </div>
            </div>

            <div x-cloak x-show="activeTab === 'custom'" class="lb-card-body lb-card-body--fill">
                <div class="lb-tab-pane">
                    @if (count($customComponents))
                        <div class="lb-component-stack lb-component-stack--flush">
                        @foreach ($customComponents as $component)
                            <button
                                data-component="{{ $component['key'] }}"
                                type="button"
                                x-on:click.stop="assignComponent(@js($component['key']))"
                                x-bind:class="{ 'is-active': selectedComponentKey() === @js($component['key']) }"
                                class="lb-component-row"
                            >
                                <svg class="lb-row-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true">{!! $component['icon'] !!}</svg>
                                <div class="lb-row-copy">
                                    <div class="lb-row-title">{{ $component['title'] }}</div>
                                    @if (filled($component['customer'] ?? null))
                                        <div class="lb-row-meta">{{ $component['customer'] }}</div>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                        </div>
                    @else
                        <p class="lb-empty-note">
                            No custom components are available for the current customer selection.
                        </p>
                    @endif
                </div>
            </div>
        </section>
    </div>
</aside>
