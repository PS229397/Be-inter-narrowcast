<?php

namespace App\Filament\Resources\Layouts\Schemas;

use App\Enums\Orientation;
use App\Filament\Resources\Layouts\LayoutResource;
use App\Filament\Layouts\LayoutBuilderField;
use App\Models\Customer;
use App\Models\CustomComponent;
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
                    ->createUrl(fn (): string => LayoutResource::getUrl('create'))
                    ->cancelUrl(fn (): string => LayoutResource::getUrl('index'))
                    ->customerOptions(
                        fn (): array => Customer::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all(),
                    )
                    ->customComponents(function (Get $get): array {
                        $customerIds = collect((array) ($get('customers') ?? []))
                            ->filter(fn (mixed $id): bool => filled($id))
                            ->map(fn (mixed $id): int => (int) $id)
                            ->values()
                            ->all();

                        return CustomComponent::query()
                            ->with('customer:id,name')
                            ->when(
                                filled($customerIds),
                                fn ($query) => $query->whereIn('customer_id', $customerIds),
                            )
                            ->orderBy('title')
                            ->get()
                            ->map(fn (CustomComponent $component): array => [
                                'id' => $component->id,
                                'key' => 'custom:' . $component->id,
                                'title' => $component->title,
                                'customer' => $component->customer?->name,
                            ])
                            ->values()
                            ->all();
                    })
                    ->default(LayoutBuilderField::emptyGrid())
                    ->required()
                    ->orientation(fn (Get $get): mixed => $get('orientation'))
                    ->columnSpanFull()
                    ->hiddenLabel(),
            ]);
    }
}
