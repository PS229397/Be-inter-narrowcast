<button
    {{
        $attributes
            ->class([
                'lb-tab-button inline-flex min-w-0 items-center justify-center gap-2 rounded-[0.85rem] border border-[var(--lb-border)] bg-[var(--lb-surface-muted)] px-[0.95rem] py-[0.8rem] text-[0.8125rem] font-semibold text-[var(--lb-text-muted)] transition-[border-color,background-color,color]',
            ])
            ->merge(['type' => 'button'])
    }}
>
    {{ $slot }}
</button>
