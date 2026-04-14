<?php

namespace App\Livewire;

use App\Filament\Layouts\LayoutBuilderField as LayoutBuilder;
use App\Models\Customer;
use App\Models\CustomComponent;
use App\Models\Layout;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Livewire\Attributes\Locked;
use Livewire\Component;

class LayoutBuilderTest extends Component implements HasForms
{
    use InteractsWithForms;

    /** @var array<string, mixed> */
    public array $data = [];

    public string $title       = '';
    public string $orientation = 'landscape';

    /** @var array<int> */
    public array $customerIds = [];

    #[Locked]
    public ?int $layoutId = null;

    /**
     * @return array<string, mixed>
     */
    protected function normalizeGridState(mixed $grid): array
    {
        if (
            is_array($grid) &&
            array_key_exists('children', $grid) &&
            is_array($grid['children'])
        ) {
            return $grid;
        }

        return LayoutBuilder::emptyGrid();
    }

    public function mount(): void
    {
        $id = request()->query('layout');

        if ($id && $layout = Layout::find($id)) {
            $this->layoutId    = $layout->id;
            $this->title       = $layout->title;
            $this->orientation = $layout->orientation->value;
            $this->customerIds = $layout->customers()->pluck('customers.id')->toArray();
            $this->form->fill(['grid' => $this->normalizeGridState($layout->grid)]);
        } else {
            $this->form->fill(['grid' => LayoutBuilder::emptyGrid()]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                LayoutBuilder::make('grid')
                    ->standalone()
                    ->customerOptions(fn (): array => $this->getCustomerOptions())
                    ->customComponents(fn (): array => $this->getCustomComponents())
                    ->orientation(fn (): string => $this->orientation)
                    ->hiddenLabel(),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        if ($this->layoutId) {
            $layout = Layout::findOrFail($this->layoutId);
            $layout->title       = $this->title;
            $layout->grid        = $state['grid'] ?? [];
            $layout->customers()->sync($this->customerIds);
            $layout->save();
        } else {
            $layout = Layout::create([
                'title'       => $this->title ?: 'Untitled',
                'orientation' => $this->orientation,
                'grid'        => $state['grid'] ?? [],
            ]);
            $layout->customers()->sync($this->customerIds);
            $this->layoutId = $layout->id;
        }
    }

    public function getCustomerOptions(): array
    {
        return Customer::pluck('name', 'id')->toArray();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCustomComponents(): array
    {
        return CustomComponent::query()
            ->with('customer:id,name')
            ->when(
                filled($this->customerIds),
                fn ($query) => $query->whereIn('customer_id', $this->customerIds),
            )
            ->orderBy('title')
            ->get()
            ->map(fn (CustomComponent $component): array => [
                'id' => $component->id,
                'key' => 'custom:' . $component->id,
                'title' => $component->title,
                'customer' => $component->customer?->name,
            ])
            ->values()
            ->all();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.layout-builder-test');
    }
}
