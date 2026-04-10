<?php

namespace App\Filament\Resources\CustomComponents\Pages;

use App\Filament\Resources\CustomComponents\CustomComponentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomComponents extends ListRecords
{
    protected static string $resource = CustomComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
