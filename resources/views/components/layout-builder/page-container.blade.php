@props([
    'maxWidth' => '1440px',
    'minLeftClearance' => '320px',
    'minRightClearance' => '40px',
])

<div
    {{ $attributes->class([
        'lb-page-container mx-auto mt-5 w-full px-4 py-8 [container-type:inline-size] [container-name:layout-page] lg:mt-0 lg:py-16 xl:px-0 lg:[--lb-page-width:min(var(--lb-page-max-width),calc(100vw-var(--lb-page-min-left-clearance)-var(--lb-page-min-right-clearance)))] lg:w-[min(100%,var(--lb-page-width))] lg:max-w-[var(--lb-page-width)] lg:ml-[max(var(--lb-page-min-left-clearance),calc((100vw-var(--lb-page-width))/2))] lg:mr-auto',
    ]) }}
    style="--lb-page-max-width: {{ $maxWidth }}; --lb-page-min-left-clearance: {{ $minLeftClearance }}; --lb-page-min-right-clearance: {{ $minRightClearance }};"
>
    {{ $slot }}
</div>
