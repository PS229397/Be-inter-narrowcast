<?php

namespace App\Filament\Admin\Resources\CustomComponentResource\Pages;

use App\Filament\Admin\Resources\CustomComponentResource;
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

