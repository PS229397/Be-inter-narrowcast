<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Orientation;
use App\Filament\Admin\Resources\LayoutResource\Pages\CreateLayout;
use App\Filament\Admin\Resources\LayoutResource\Pages\EditLayout;
use App\Filament\Admin\Resources\LayoutResource\Pages\ListLayouts;
use App\Filament\Admin\Resources\LayoutResource\Pages\ViewLayout;
use App\Filament\Layouts\LayoutBuilderField;
use App\Models\CustomComponent;
use App\Models\Customer;
use App\Models\Layout;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class LayoutResource extends Resource
{
    protected static ?string $model = Layout::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Squares2x2;

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Content';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
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
                ->createUrl(fn (): string => static::getUrl('create'))
                ->cancelUrl(fn (): string => static::getUrl('index'))
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

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Layout Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('title'),
                    TextEntry::make('orientation')->badge(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('orientation')
                    ->badge()
                    ->sortable(),
                TextColumn::make('customers_count')
                    ->counts('customers')
                    ->label('Customers')
                    ->sortable()
                    ->formatStateUsing(fn (string|int|null $state): string => (int) $state === 0 ? 'All customers' : (string) $state),
            TextColumn::make('slides_count')
                    ->counts('slides')
                    ->label('Slides')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('orientation')
                    ->options(Orientation::class),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLayouts::route('/'),
            'create' => CreateLayout::route('/create'),
            'view' => ViewLayout::route('/{record}'),
            'edit' => EditLayout::route('/{record}/edit'),
        ];
    }
}
