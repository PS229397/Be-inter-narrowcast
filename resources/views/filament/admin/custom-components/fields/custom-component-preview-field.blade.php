<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $bladePath = $getBladeStatePath() ?? 'data.blade';
        $phpPath = $getPhpStatePath() ?? 'data.php';
        $jsPath = $getJsStatePath() ?? 'data.js';
        $scssPath = $getScssStatePath() ?? 'data.scss';
    @endphp

    <div
        x-load
        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('custom-component-preview', package: 'app') }}"
        x-data="customComponentPreview({
            blade: $wire.{{ $applyStateBindingModifiers("\$entangle('{$bladePath}')", isOptimisticallyLive: false) }},
            php: $wire.{{ $applyStateBindingModifiers("\$entangle('{$phpPath}')", isOptimisticallyLive: false) }},
            js: $wire.{{ $applyStateBindingModifiers("\$entangle('{$jsPath}')", isOptimisticallyLive: false) }},
            scss: $wire.{{ $applyStateBindingModifiers("\$entangle('{$scssPath}')", isOptimisticallyLive: false) }},
        })"
        class="rounded-xl border border-gray-200 bg-white p-3 dark:border-white/10 dark:bg-white/5"
    >
        <div class="mb-3">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Preview</p>
        </div>

        <div class="w-full">
            <div class="aspect-video w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-50 shadow-sm dark:border-white/10 dark:bg-gray-900">
                <iframe
                    x-bind:srcdoc="previewDoc()"
                    title="Custom component preview"
                    class="h-full w-full border-0 bg-transparent"
                    sandbox="allow-scripts"
                ></iframe>
            </div>
        </div>
    </div>
</x-dynamic-component>
