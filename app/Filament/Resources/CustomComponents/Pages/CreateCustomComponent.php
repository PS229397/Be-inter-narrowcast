<?php

namespace App\Filament\Resources\CustomComponents\Pages;

use App\Filament\Resources\CustomComponents\CustomComponentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomComponent extends CreateRecord
{
    protected static string $resource = CustomComponentResource::class;
}
