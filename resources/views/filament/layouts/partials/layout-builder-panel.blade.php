<aside
    class="lb-panel"
    x-on:click.stop
>
    <div x-data="{ baseOpen: true }" class="lb-panel-scroll">
        <section class="lb-card">
            <button
                type="button"
                x-on:click="baseOpen = ! baseOpen"
                class="lb-card-toggle"
            >
                <span class="lb-card-title">Base components</span>
                <svg
                    class="lb-chevron"
                    x-bind:style="baseOpen ? 'transform: rotate(180deg);' : ''"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                </svg>
            </button>

            <div x-cloak x-show="baseOpen" class="lb-card-body">
                <div class="lb-component-grid">
                    @foreach ($baseComponents as $component)
                        <button
                            data-component="{{ $component['key'] }}"
                            type="button"
                            x-on:click.stop="assignComponent(@js($component['key']))"
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
        </section>

        <section class="lb-card">
            <div class="lb-card-header">
                <p class="lb-card-title">Custom components</p>
                <span class="lb-card-count">{{ count($customComponents) }}</span>
            </div>

            @if (count($customComponents))
                <div class="lb-component-stack">
                    @foreach ($customComponents as $component)
                        <button
                            data-component="{{ $component['key'] }}"
                            type="button"
                            x-on:click.stop="assignComponent(@js($component['key']))"
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
                <div class="lb-component-stack">
                    <p class="lb-empty-note">
                        No custom components are available for the current customer selection.
                    </p>
                </div>
            @endif
        </section>
    </div>
</aside>
