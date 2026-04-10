<?php

namespace App\Filament\App\Resources\Slideshows\Pages;

use App\Filament\App\Resources\Slideshows\SlideshowResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewSlideshow extends ViewRecord
{
    protected static string $resource = SlideshowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('open_display')
                ->label('Open Display')
                ->icon(Heroicon::ArrowTopRightOnSquare)
                ->color('gray')
                ->url(fn (): string => SlideshowResource::getDisplayUrl($this->getRecord()))
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }
}
