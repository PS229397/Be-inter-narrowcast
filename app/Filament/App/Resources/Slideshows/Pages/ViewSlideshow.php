<?php

namespace App\Filament\App\Resources\Slideshows\Pages;

use App\Filament\App\Resources\Slideshows\SlideshowResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSlideshow extends ViewRecord
{
    protected static string $resource = SlideshowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
