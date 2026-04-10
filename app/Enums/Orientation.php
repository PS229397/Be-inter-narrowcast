<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Orientation: string implements HasColor, HasLabel
{
    case Portrait = 'portrait';
    case Landscape = 'landscape';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Portrait => 'Portrait',
            self::Landscape => 'Landscape',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Portrait => 'info',
            self::Landscape => 'warning',
        };
    }
}

