<?php

namespace App\Filament\Layouts;

use App\Rules\ValidLayoutGrid;
use App\Support\Layouts\LayoutGrid;
use BackedEnum;
use Closure;
use Filament\Forms\Components\Field;
use UnitEnum;

class LayoutBuilderField extends Field
{
    protected string $view = 'filament.layouts.layout-builder-field';

    protected string|Closure|null $orientation = null;

    protected string|Closure|null $titleStatePath = null;

    protected string|Closure|null $orientationStatePath = null;

    protected string|Closure|null $customersStatePath = null;

    protected string|Closure|null $submitAction = null;

    protected string|Closure|null $submitFormId = null;

    protected string|Closure|null $cancelUrl = null;

    protected bool|Closure $standalone = false;

    protected bool|Closure|null $editing = null;

    /**
     * @var array<int|string, string> | Closure
     */
    protected array|Closure $customerOptions = [];

    /**
     * @var array<int, array<string, mixed>> | Closure
     */
    protected array|Closure $baseComponents = [];

    /**
     * @var array<int, array<string, mixed>> | Closure
     */
    protected array|Closure $customComponents = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->default(LayoutGrid::empty());
        $this->formatStateUsing(fn (mixed $state): array => LayoutGrid::normalize($state));
        $this->dehydrateStateUsing(fn (mixed $state): array => LayoutGrid::normalize($state));
        $this->rule(new ValidLayoutGrid);
    }

    public static function emptyGrid(): array
    {
        return LayoutGrid::empty();
    }

    public function orientation(string|Closure|null $orientation): static
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function titleStatePath(string|Closure|null $path): static
    {
        $this->titleStatePath = $path;

        return $this;
    }

    public function orientationStatePath(string|Closure|null $path): static
    {
        $this->orientationStatePath = $path;

        return $this;
    }

    public function customersStatePath(string|Closure|null $path): static
    {
        $this->customersStatePath = $path;

        return $this;
    }

    public function submitAction(string|Closure|null $action): static
    {
        $this->submitAction = $action;

        return $this;
    }

    public function submitFormId(string|Closure|null $id): static
    {
        $this->submitFormId = $id;

        return $this;
    }

    public function cancelUrl(string|Closure|null $url): static
    {
        $this->cancelUrl = $url;

        return $this;
    }

    public function standalone(bool|Closure $condition = true): static
    {
        $this->standalone = $condition;

        return $this;
    }

    public function editing(bool|Closure $condition = true): static
    {
        $this->editing = $condition;

        return $this;
    }

    /**
     * @param  array<int|string, string> | Closure  $options
     */
    public function customerOptions(array|Closure $options): static
    {
        $this->customerOptions = $options;

        return $this;
    }

    /**
     * @param  array<int, array<string, mixed>> | Closure  $components
     */
    public function baseComponents(array|Closure $components): static
    {
        $this->baseComponents = $components;

        return $this;
    }

    /**
     * @param  array<int, array<string, mixed>> | Closure  $components
     */
    public function customComponents(array|Closure $components): static
    {
        $this->customComponents = $components;

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

    public function getTitleStatePath(): ?string
    {
        $path = $this->evaluate($this->titleStatePath);

        if (filled($path)) {
            return (string) $path;
        }

        return $this->isStandalone() ? 'title' : null;
    }

    public function getOrientationStatePath(): ?string
    {
        $path = $this->evaluate($this->orientationStatePath);

        if (filled($path)) {
            return (string) $path;
        }

        return $this->isStandalone() ? 'orientation' : null;
    }

    public function getCustomersStatePath(): ?string
    {
        $path = $this->evaluate($this->customersStatePath);

        if (filled($path)) {
            return (string) $path;
        }

        return $this->isStandalone() ? 'customerIds' : null;
    }

    public function getSubmitAction(): string
    {
        $action = $this->evaluate($this->submitAction);

        if (filled($action)) {
            return (string) $action;
        }

        return $this->isEditing() ? 'save' : 'create';
    }

    public function getSubmitFormId(): ?string
    {
        $id = $this->evaluate($this->submitFormId);

        return filled($id) ? (string) $id : null;
    }

    public function getCancelUrl(): ?string
    {
        $url = $this->evaluate($this->cancelUrl);

        return filled($url) ? (string) $url : null;
    }

    public function isEditing(): bool
    {
        if ($this->editing !== null) {
            return (bool) $this->evaluate($this->editing);
        }

        return rescue(
            fn (): bool => $this->getContainer()->getOperation() === 'edit',
            false,
            report: false,
        );
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

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getBaseComponents(): array
    {
        /** @var array<int, array<string, mixed>> $components */
        $components = $this->evaluate($this->baseComponents);

        return $components;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCustomComponents(): array
    {
        /** @var array<int, array<string, mixed>> $components */
        $components = $this->evaluate($this->customComponents);

        return $components;
    }
}
