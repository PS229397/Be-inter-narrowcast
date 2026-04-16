<div
    {{ $attributes->class([
        'min-h-0 flex-1 overflow-hidden rounded-b-[inherit] border-t-0 p-0',
    ]) }}
>
    <div class="grid h-full min-h-0 content-start gap-3.5 overflow-y-auto p-4">
        {{ $slot }}
    </div>
</div>
