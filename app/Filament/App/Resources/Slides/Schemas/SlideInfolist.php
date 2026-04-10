<?php

namespace App\Filament\App\Resources\Slides\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SlideInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Slide Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('layout.title')->label('Layout'),
                        TextEntry::make('layout.orientation')->badge()->label('Orientation'),
                        TextEntry::make('category.title')->label('Category')->default('—'),
                        IconEntry::make('is_active')->boolean()->label('Active'),
                        TextEntry::make('duration_in_seconds')->suffix('s')->label('Duration'),
                        TextEntry::make('start_date')->date()->label('Start Date'),
                        TextEntry::make('end_date')->date()->label('End Date'),
                    ]),
            ]);
    }
}
