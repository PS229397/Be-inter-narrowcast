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

    protected bool | Closure $standalone = false;

    /**
     * @var array<int|string, string> | Closure
     */
    protected array | Closure $customerOptions = [];

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

    public function standalone(bool | Closure $condition = true): static
    {
        $this->standalone = $condition;

        return $this;
    }

    /**
     * @param  array<int|string, string> | Closure  $options
     */
    public function customerOptions(array | Closure $options): static
    {
        $this->customerOptions = $options;

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

    public function isStandalone(): bool
    {
        return (bool) $this->evaluate($this->standalone);
    }

    /**
     * @return array<int|string, string>
     */
    public function getCustomerOptions(): array
    {
        /** @var array<int|string, string> $options */
        $options = $this->evaluate($this->customerOptions);

        return $options;
    }
}
