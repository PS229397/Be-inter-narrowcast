@props([
    'dashed' => false,
])

<p
    {{ $attributes->class([
        'rounded-[0.9rem] border bg-[var(--lb-surface)] px-[0.875rem] py-3 text-xs leading-[1.4] text-[var(--lb-text-muted)]',
        'border-[var(--lb-border-soft)]' => ! $dashed,
        'border-[var(--lb-border-soft)] border-dashed' => $dashed,
    ]) }}
>
    {{ $slot }}
</p>
