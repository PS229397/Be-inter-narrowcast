<?php

namespace App\Filament\App\Resources\Slideshows\Schemas;

use App\Filament\App\Resources\Slideshows\SlideshowResource;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SlideshowInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Slideshow Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('title'),
                        IconEntry::make('is_active')->boolean()->label('Active'),
                        TextEntry::make('slides_count')
                            ->label('Slides')
                            ->state(fn ($record) => $record->slides()->count()),
                        TextEntry::make('locations_count')
                            ->label('Locations')
                            ->state(fn ($record) => $record->locations()->count()),
                        TextEntry::make('display_url')
                            ->label('Display URL')
                            ->state(fn ($record) => SlideshowResource::getDisplayUrl($record))
                            ->copyable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
