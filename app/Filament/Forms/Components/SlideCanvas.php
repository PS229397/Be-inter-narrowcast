<?php

namespace App\Filament\Forms\Components;

use App\Filament\Layouts\LayoutBuilderField;
use App\Models\Layout;
use Closure;

class SlideCanvas extends LayoutBuilderField
{
    protected string $view = 'filament.forms.components.slide-canvas';

    protected Layout|Closure|null $layout = null;

    public function layout(Layout|Closure|null $layout): static
    {
        $this->layout = $layout;

        return $this;
    }

    public function getLayout(): ?Layout
    {
        return $this->evaluate($this->layout);
    }

    public function getOrientation(): ?string
    {
        return $this->getLayout()?->orientation?->value
            ?? parent::getOrientation();
    }
}
