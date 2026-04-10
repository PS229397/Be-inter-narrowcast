<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @php($orientation = $getOrientation())

    <div
        x-data="{ state: $wire.$entangle(@js($getStatePath())) }"
        {{ $getExtraAttributeBag() }}
    >
        <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-900/40">
            <div class="space-y-2">
                <p class="text-sm font-semibold text-gray-950 dark:text-white">
                    Layout builder is not part of this sprint.
                </p>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Layouts can already be created, assigned to customers, and linked from slides.
                    Interactive grid slicing and canvas editing will land in a follow-up sprint.
                </p>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Orientation:
                    <span class="font-medium text-gray-700 dark:text-gray-200">
                        {{ filled($orientation) ? str($orientation)->headline() : 'Choose an orientation first' }}
                    </span>
                </p>
            </div>

            <template x-if="Array.isArray(state) ? state.length : !!state">
                <pre
                    x-text="JSON.stringify(state, null, 2)"
                    class="mt-4 overflow-x-auto rounded-lg bg-white p-4 text-xs text-gray-700 shadow-sm dark:bg-gray-950 dark:text-gray-200"
                ></pre>
            </template>
        </div>
    </div>
</x-dynamic-component>
