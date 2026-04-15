<?php

namespace App\Filament\Admin\Resources\LayoutResource\Schemas;

use App\Enums\Orientation;
use App\Filament\Admin\Resources\LayoutResource;
use App\Filament\Layouts\LayoutBuilderField;
use App\Models\Customer;
use App\Support\Layouts\LayoutGrid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class LayoutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->default('Untitled layout')
                    ->required()
                    ->maxLength(255)
                    ->live()
                    ->hidden()
                    ->dehydratedWhenHidden(),
                Select::make('orientation')
                    ->options(Orientation::class)
                    ->default(Orientation::Landscape)
                    ->required()
                    ->live()
                    ->disabledOn('edit')
                    ->hidden()
                    ->dehydratedWhenHidden(),
                Select::make('customers')
                    ->relationship('customers', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->hidden()
                    ->dehydratedWhenHidden()
                    ->saveRelationshipsWhenHidden(),
                LayoutBuilderField::make('grid')
                    ->standalone()
                    ->editing(fn (string $operation): bool => $operation === 'edit')
                    ->submitAction(fn (string $operation): string => $operation === 'edit' ? 'save' : 'create')
                    ->submitFormId('form')
                    ->titleStatePath('data.title')
                    ->orientationStatePath('data.orientation')
                    ->customersStatePath('data.customers')
                    ->cancelUrl(fn (): string => LayoutResource::getUrl('index'))
                    ->customerOptions(
                        fn (): array => Customer::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all(),
                    )
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
