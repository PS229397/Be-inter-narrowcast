@props([
    'subtle' => false,
])

<div
    {{ $attributes->class([
        'rounded-2xl border border-[var(--lb-border)] text-[var(--lb-text)] shadow-[var(--lb-shadow)] transition-[border-color,background-color,box-shadow,color]',
        'bg-[var(--lb-surface-subtle)]' => $subtle,
        'bg-[var(--lb-surface)]' => ! $subtle,
    ]) }}
>
    {{ $slot }}
</div>
