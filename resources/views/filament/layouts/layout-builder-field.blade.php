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
            $isEditing = $isEditing();
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
            wire:key="layout-builder-{{ md5($statePath) }}-{{ $orientation }}-{{ $catalogKey }}"
            class="lb-builder is-standalone mx-auto flex flex-col gap-3"
            {{ $builderExtraAttrs }}
        >
            <div class="lb-standalone-layout">
                @include('filament.layouts.partials.layout-builder-canvas')

                <div class="lb-standalone-sidebar">
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
