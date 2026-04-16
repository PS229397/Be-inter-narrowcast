<button
    {{
        $attributes
            ->class([
                'lb-component-button flex aspect-square flex-col items-center justify-center gap-1.5 rounded-[0.9rem] border border-[var(--lb-border)] bg-[var(--lb-surface-muted)] px-2 py-3 text-[var(--lb-text-muted)] transition-[border-color,color,background-color]',
            ])
            ->merge(['type' => 'button'])
    }}
>
    {{ $slot }}
</button>
