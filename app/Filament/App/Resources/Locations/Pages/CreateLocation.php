<?php

namespace App\Filament\App\Resources\Locations\Pages;

use App\Filament\App\Resources\Locations\LocationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocation extends CreateRecord
{
    protected static string $resource = LocationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['customer_id'] = auth()->user()->customer_id;

        return $data;
    }
}
