<?php

namespace App\Filament\Admin\Resources\LayoutResource\Pages;

use App\Filament\Admin\Resources\LayoutResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLayout extends EditRecord
{
    protected static string $resource = LayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

