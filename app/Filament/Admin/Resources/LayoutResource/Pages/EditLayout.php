<?php

namespace App\Filament\Admin\Resources\LayoutResource\Pages;

use App\Filament\Admin\Resources\LayoutResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditLayout extends EditRecord
{
    protected static string $resource = LayoutResource::class;

    protected string $view = 'filament.resources.layouts.pages.layout-builder-record';

    protected Width | string | null $maxContentWidth = Width::Screen;

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return LayoutResource::getUrl('index');
    }
}

