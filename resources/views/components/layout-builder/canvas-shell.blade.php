<section
    {{ $attributes->class([
        'relative flex box-border flex-col gap-3 overflow-hidden rounded-2xl border border-[var(--lb-border)] bg-[var(--lb-surface)] p-4 text-[var(--lb-text)] shadow-[var(--lb-shadow)] transition-[border-color,background-color,box-shadow,color]',
    ]) }}
>
    {{ $slot }}
</section>
