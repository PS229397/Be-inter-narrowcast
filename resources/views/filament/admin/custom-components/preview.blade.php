<div
    x-data="{ width: 50 }"
    class="rounded-xl border border-gray-200 bg-white p-3"
>
    <div class="mb-2 flex items-center justify-between">
        <p class="text-xs font-medium text-gray-700">Preview</p>
        <input
            type="range"
            min="30"
            max="100"
            x-model="width"
            class="w-32"
        >
    </div>

    <div
        class="mx-auto min-h-40 rounded border border-dashed border-gray-300 bg-gray-50 p-3 text-xs text-gray-500"
        :style="`width: ${width}%`"
    >
        Preview area for Blade/SCSS/JS output.
    </div>
</div>

