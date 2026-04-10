<?php

namespace App\Filament\Admin\Resources\CustomComponentResource\Pages;

use App\Filament\Admin\Resources\CustomComponentResource;
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

