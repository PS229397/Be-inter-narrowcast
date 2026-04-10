<?php

namespace App\Filament\App\Resources\Slides\Pages;

use App\Filament\App\Resources\Slides\SlideResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSlide extends CreateRecord
{
    protected static string $resource = SlideResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['customer_id'] = auth()->user()->customer_id;

        return $data;
    }
}
