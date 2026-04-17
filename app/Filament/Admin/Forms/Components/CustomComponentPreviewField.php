<?php

namespace App\Filament\Admin\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;

class CustomComponentPreviewField extends Field
{
    protected string $view = 'filament.admin.custom-components.fields.custom-component-preview-field';

    protected string|Closure|null $bladeStatePath = 'data.blade';

    protected string|Closure|null $phpStatePath = 'data.php';

    protected string|Closure|null $jsStatePath = 'data.js';

    protected string|Closure|null $scssStatePath = 'data.scss';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrated(false);
    }

    public function bladeStatePath(string|Closure|null $path): static
    {
        $this->bladeStatePath = $path;

        return $this;
    }

    public function phpStatePath(string|Closure|null $path): static
    {
        $this->phpStatePath = $path;

        return $this;
    }

    public function jsStatePath(string|Closure|null $path): static
    {
        $this->jsStatePath = $path;

        return $this;
    }

    public function scssStatePath(string|Closure|null $path): static
    {
        $this->scssStatePath = $path;

        return $this;
    }

    public function getBladeStatePath(): ?string
    {
        $path = $this->evaluate($this->bladeStatePath);

        return filled($path) ? (string) $path : null;
    }

    public function getPhpStatePath(): ?string
    {
        $path = $this->evaluate($this->phpStatePath);

        return filled($path) ? (string) $path : null;
    }

    public function getJsStatePath(): ?string
    {
        $path = $this->evaluate($this->jsStatePath);

        return filled($path) ? (string) $path : null;
    }

    public function getScssStatePath(): ?string
    {
        $path = $this->evaluate($this->scssStatePath);

        return filled($path) ? (string) $path : null;
    }
}

