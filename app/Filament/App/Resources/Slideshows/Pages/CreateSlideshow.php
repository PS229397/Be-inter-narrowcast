<?php

namespace App\Filament\App\Resources\Slideshows\Pages;

use App\Filament\App\Resources\Slideshows\SlideshowResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSlideshow extends CreateRecord
{
    protected static string $resource = SlideshowResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['customer_id'] = auth()->user()->customer_id;

        return $data;
    }
}
