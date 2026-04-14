<?php

namespace App\Filament\Forms\Components;

use BackedEnum;
use Closure;
use Filament\Forms\Components\Field;
use UnitEnum;

class LayoutBuilder extends Field
{
    protected string $view = 'filament.forms.components.layout-builder';

    protected string | Closure | null $orientation = null;

    /**
     * @return array<string, mixed>
     */
    public static function emptyGrid(): array
    {
        return [
            'id' => 'root',
            'direction' => null,
            'split' => 50,
            'children' => [],
            'component' => null,
        ];
    }

    public function orientation(string | Closure | null $orientation): static
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function getOrientation(): ?string
    {
        $orientation = $this->evaluate($this->orientation);

        if ($orientation instanceof BackedEnum) {
            return (string) $orientation->value;
        }

        if ($orientation instanceof UnitEnum) {
            return $orientation->name;
        }

        return filled($orientation) ? (string) $orientation : null;
    }
}
