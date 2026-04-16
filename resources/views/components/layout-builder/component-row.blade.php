<button
    {{
        $attributes
            ->class([
                'lb-component-row flex w-full items-center gap-3 rounded-[0.9rem] border border-[var(--lb-border)] bg-[var(--lb-surface-muted)] px-[0.9rem] py-[0.85rem] text-left text-[var(--lb-text-muted)] transition-[border-color,color,background-color]',
            ])
            ->merge(['type' => 'button'])
    }}
>
    {{ $slot }}
</button>
