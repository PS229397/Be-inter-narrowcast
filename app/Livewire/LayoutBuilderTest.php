<?php

namespace App\Livewire;

use App\Enums\Orientation;
use App\Filament\Forms\Components\LayoutBuilder;
use App\Models\Customer;
use App\Models\Layout;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class LayoutBuilderTest extends Component implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;

    /** @var array<string, mixed> */
    public array $data = [];

    public string $title       = '';
    public string $orientation = 'landscape';

    /** @var array<int> */
    public array $customerIds = [];

    #[Locked]
    public ?int $layoutId = null;

    // Temporary upload slot — reused for every single-file upload
    public $uploadedFile = null;

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

    /**
     * Called from Alpine after $wire.upload() completes.
     * Moves the temp file to permanent public storage and returns the URL.
     */
    public function persistUpload(): string
    {
        if (! $this->uploadedFile) {
            return '';
        }

        $path = $this->uploadedFile->storePublicly('slide-media', 'public');
        $this->uploadedFile = null;

        return Storage::disk('public')->url($path);
    }

    public function getCustomerOptions(): array
    {
        return Customer::pluck('name', 'id')->toArray();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.layout-builder-test', [
            'customers' => Customer::orderBy('name')->pluck('name', 'id'),
        ]);
    }
}
