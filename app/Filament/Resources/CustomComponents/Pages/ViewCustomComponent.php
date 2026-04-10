<?php

namespace App\Filament\Resources\CustomComponents\Pages;

use App\Filament\Resources\CustomComponents\CustomComponentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomComponent extends ViewRecord
{
    protected static string $resource = CustomComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
