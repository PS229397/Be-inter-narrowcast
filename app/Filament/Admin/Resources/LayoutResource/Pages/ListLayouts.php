<?php

namespace App\Filament\Admin\Resources\LayoutResource\Pages;

use App\Filament\Admin\Resources\LayoutResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLayouts extends ListRecords
{
    protected static string $resource = LayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

