<?php

namespace App\Filament\Resources\Layouts\Schemas;

use App\Enums\Orientation;
use App\Filament\Forms\Components\LayoutBuilderField;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class LayoutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Layout Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Select::make('orientation')
                            ->options(Orientation::class)
                            ->default(Orientation::Landscape)
                            ->required()
                            ->live()
                            ->disabledOn('edit')
                            ->helperText('Cannot be changed after creation.'),
                        Select::make('customers')
                            ->relationship('customers', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->label('Assign to Customers')
                            ->columnSpanFull(),
                    ]),
                Section::make('Grid Builder')
                    ->columnSpanFull()
                    ->schema([
                        LayoutBuilderField::make('grid')
                            ->default(LayoutBuilderField::emptyGrid())
                            ->required()
                            ->orientation(fn (Get $get): mixed => $get('orientation'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
