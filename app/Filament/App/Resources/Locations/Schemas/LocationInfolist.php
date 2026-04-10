<?php

namespace App\Filament\App\Resources\Locations\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LocationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Location Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('slug')->copyable(),
                        TextEntry::make('orientation')->badge(),
                    ]),
            ]);
    }
}
