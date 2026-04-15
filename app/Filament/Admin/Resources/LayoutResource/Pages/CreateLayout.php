<?php

namespace App\Filament\Admin\Resources\LayoutResource\Pages;

use App\Filament\Admin\Resources\LayoutResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateLayout extends CreateRecord
{
    protected static string $resource = LayoutResource::class;

    protected string $view = 'filament.resources.layouts.pages.layout-builder-record';

    protected Width | string | null $maxContentWidth = Width::Screen;

    protected function getFormActions(): array
    {
        return [];
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
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
}

