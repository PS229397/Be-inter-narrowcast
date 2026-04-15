<?php

namespace App\Filament\Admin\Resources\LayoutResource\Pages;

use App\Filament\Admin\Resources\LayoutResource;
use App\Filament\Admin\Resources\LayoutResource\Pages\Concerns\InteractsWithLayoutData;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;

class EditLayout extends EditRecord
{
    use InteractsWithLayoutData;

    protected static string $resource = LayoutResource::class;

    protected string $view = 'filament.resources.layouts.pages.layout-builder-record';

    protected Width|string|null $maxContentWidth = Width::Screen;

    protected function getFormActions(): array
    {
        return [];
    }

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return LayoutResource::getUrl('index');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->normalizeLayoutData($data);
    }
}
