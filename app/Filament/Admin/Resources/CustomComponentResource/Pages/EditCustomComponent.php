<?php

namespace App\Filament\Admin\Resources\CustomComponentResource\Pages;

use App\Filament\Admin\Resources\CustomComponentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomComponent extends EditRecord
{
    protected static string $resource = CustomComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

