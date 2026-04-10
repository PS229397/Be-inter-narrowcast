<?php

namespace App\Filament\App\Resources\Locations\Schemas;

use App\Enums\Orientation;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Location Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Amsterdam Office — Lobby'),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: 'locations', column: 'slug', ignoreRecord: true)
                            ->helperText('URL-safe identifier for this screen location.'),
                        Select::make('orientation')
                            ->options(Orientation::class)
                            ->default(Orientation::Landscape)
                            ->required()
                            ->helperText('Set manually. Orientation matching rules are deferred for this sprint.'),
                    ]),
            ]);
    }
}
