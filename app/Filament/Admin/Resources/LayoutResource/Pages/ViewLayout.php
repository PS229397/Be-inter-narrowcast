<?php

namespace App\Filament\Admin\Resources\LayoutResource\Pages;

use App\Filament\Admin\Resources\LayoutResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLayout extends ViewRecord
{
    protected static string $resource = LayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

