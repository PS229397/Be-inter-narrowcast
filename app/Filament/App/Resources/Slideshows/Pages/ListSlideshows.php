<?php

namespace App\Filament\App\Resources\Slideshows\Pages;

use App\Filament\App\Resources\Slideshows\SlideshowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSlideshows extends ListRecords
{
    protected static string $resource = SlideshowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
