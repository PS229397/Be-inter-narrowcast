<?php

namespace App\Filament\Admin\Resources\LayoutResource\Schemas;

use App\Enums\Orientation;
use App\Filament\Admin\Resources\LayoutResource;
use App\Filament\Layouts\LayoutBuilderField;
use App\Support\Layouts\LayoutGrid;
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
                Section::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(),
                        Select::make('orientation')
                            ->options(Orientation::class)
                            ->default(Orientation::Landscape)
                            ->required()
                            ->live()
                            ->disabledOn('edit'),
                        Select::make('customers')
                            ->relationship('customers', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->live(),
                    ]),
                LayoutBuilderField::make('grid')
                    ->standalone()
                    ->editing(fn (string $operation): bool => $operation === 'edit')
                    ->titleStatePath('data.title')
                    ->required()
                    ->default(LayoutGrid::empty())
                    ->orientation(fn (Get $get): mixed => $get('orientation'))
                    ->baseComponents(LayoutResource::getBaseComponents())
                    ->customComponents(fn (Get $get): array => LayoutResource::getCustomComponents(
                        customerIds: (array) ($get('customers') ?? []),
                    ))
                    ->columnSpanFull()
                    ->hiddenLabel(),
            ]);
    }
}
