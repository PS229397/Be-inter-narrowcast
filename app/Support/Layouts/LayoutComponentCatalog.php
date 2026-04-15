<?php

namespace App\Support\Layouts;

class LayoutComponentCatalog
{
    /**
     * @return array<int, array{key: string, label: string, icon: string, type: string}>
     */
    public static function baseComponents(): array
    {
        return [
            [
                'key' => 'text',
                'label' => 'Text',
                'icon' => '<path d="M5 5h10M10 5v10M7 15h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                'type' => 'base',
            ],
            [
                'key' => 'image',
                'label' => 'Image',
                'icon' => '<rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/><circle cx="7.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M2 13.5l4-4 3 3 2.5-2.5 4.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                'type' => 'base',
            ],
            [
                'key' => 'video',
                'label' => 'Video',
                'icon' => '<rect x="2" y="5" width="12" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M14 8.5l4-2v5l-4-2V8.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>',
                'type' => 'base',
            ],
            [
                'key' => 'carousel',
                'label' => 'Carousel',
                'icon' => '<rect x="1" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1.5" opacity="0.4"/><rect x="6" y="3" width="8" height="11" rx="1.5" stroke="currentColor" stroke-width="1.5"/><rect x="15" y="6" width="4" height="7" rx="1" stroke="currentColor" stroke-width="1.5" opacity="0.4"/><circle cx="8.5" cy="17.5" r="0.75" fill="currentColor"/><circle cx="10" cy="17.5" r="0.75" fill="currentColor"/><circle cx="11.5" cy="17.5" r="0.75" fill="currentColor"/>',
                'type' => 'base',
            ],
            [
                'key' => 'ticker',
                'label' => 'Ticker',
                'icon' => '<rect x="2" y="7" width="16" height="6" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M5 10h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M13 8.5l2.5 1.5-2.5 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                'type' => 'base',
            ],
            [
                'key' => 'clock',
                'label' => 'Clock',
                'icon' => '<circle cx="10" cy="10" r="7.5" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                'type' => 'base',
            ],
            [
                'key' => 'weather',
                'label' => 'Weather',
                'icon' => '<circle cx="10" cy="8.5" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M10 3v1M10 14v1M3.5 8.5h1M15.5 8.5h1M5.6 4.6l.7.7M13.7 4.6l-.7.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M5 16a3 3 0 010-6h.5a4 4 0 017 0H13a3 3 0 010 6H5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>',
                'type' => 'base',
            ],
            [
                'key' => 'countdown',
                'label' => 'Countdown',
                'icon' => '<circle cx="10" cy="11" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M10 8v3l-2.5 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 3h4M10 1v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
                'type' => 'base',
            ],
            [
                'key' => 'qr',
                'label' => 'QR Code',
                'icon' => '<rect x="3" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/><rect x="12" y="3" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/><rect x="3" y="12" width="5" height="5" rx="0.5" stroke="currentColor" stroke-width="1.5"/><rect x="4.5" y="4.5" width="2" height="2" fill="currentColor"/><rect x="13.5" y="4.5" width="2" height="2" fill="currentColor"/><rect x="4.5" y="13.5" width="2" height="2" fill="currentColor"/><path d="M12 12h2v2h-2zM14 14h2v2h-2zM12 16h2M16 12v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                'type' => 'base',
            ],
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function baseKeys(): array
    {
        return array_column(static::baseComponents(), 'key');
    }

    public static function customIcon(): string
    {
        return '<path d="M4 8.5A2.5 2.5 0 0 1 6.5 6H8V4.5A1.5 1.5 0 0 1 9.5 3h1A1.5 1.5 0 0 1 12 4.5V6h1.5A2.5 2.5 0 0 1 16 8.5v1A2.5 2.5 0 0 1 13.5 12H12v1.5A1.5 1.5 0 0 1 10.5 15h-1A1.5 1.5 0 0 1 8 13.5V12H6.5A2.5 2.5 0 0 1 4 9.5v-1Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8 9h4M10 7v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>';
    }

    public static function isBaseComponentKey(?string $key): bool
    {
        return filled($key) && in_array($key, static::baseKeys(), true);
    }

    public static function isCustomComponentKey(?string $key): bool
    {
        return filled($key) && preg_match('/^custom:\d+$/', $key) === 1;
    }

    public static function resolveType(?string $key): ?string
    {
        if (! filled($key)) {
            return null;
        }

        if (static::isBaseComponentKey($key)) {
            return 'base';
        }

        if (static::isCustomComponentKey($key)) {
            return 'custom';
        }

        return null;
    }
}
