<?php

namespace App\Filament\Resources\Layouts\Pages;

use App\Filament\Resources\Layouts\LayoutResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
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

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
