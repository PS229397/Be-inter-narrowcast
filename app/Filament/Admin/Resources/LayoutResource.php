<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LayoutResource\Pages\CreateLayout;
use App\Filament\Admin\Resources\LayoutResource\Pages\EditLayout;
use App\Filament\Admin\Resources\LayoutResource\Pages\ListLayouts;
use App\Filament\Admin\Resources\LayoutResource\Schemas\LayoutForm;
use App\Filament\Admin\Resources\LayoutResource\Tables\LayoutsTable;
use App\Models\CustomComponent;
use App\Models\Layout;
use App\Support\Layouts\LayoutComponentCatalog;
use App\Support\Layouts\LayoutGrid;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
        return LayoutForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LayoutsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLayouts::route('/'),
            'create' => CreateLayout::route('/create'),
            'edit' => EditLayout::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<int, array{key: string, label: string, icon: string, type: string}>
     */
    public static function getBaseComponents(): array
    {
        return LayoutComponentCatalog::baseComponents();
    }

    /**
     * @param  array<int, int|string>  $customerIds
     * @return array<int, array{id: int, key: string, title: string, customer: ?string, icon: string, type: string}>
     */
    public static function getCustomComponents(array $customerIds = []): array
    {
        $normalizedCustomerIds = collect($customerIds)
            ->filter(fn (mixed $id): bool => filled($id))
            ->map(fn (mixed $id): int => (int) $id)
            ->values()
            ->all();

        if (count($normalizedCustomerIds) !== 1) {
            return [];
        }

        return CustomComponent::query()
            ->with('customer:id,name')
            ->where('customer_id', $normalizedCustomerIds[0])
            ->orderBy('title')
            ->get()
            ->map(fn (CustomComponent $component): array => [
                'id' => $component->id,
                'key' => 'custom:'.$component->id,
                'title' => $component->title,
                'customer' => $component->customer?->name,
                'icon' => LayoutComponentCatalog::customIcon(),
                'type' => 'custom',
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<int, int|string>  $customerIds
     * @return array<int, string>
     */
    public static function getAllowedComponentKeys(array $customerIds = []): array
    {
        return array_values(array_unique([
            ...LayoutComponentCatalog::baseKeys(),
            ...array_column(static::getCustomComponents($customerIds), 'key'),
        ]));
    }

    /**
     * @param  array<int, int|string>  $customerIds
     * @return array<int, string>
     */
    public static function getInvalidGridComponentKeys(mixed $grid, array $customerIds = []): array
    {
        return LayoutGrid::invalidComponentKeys(
            raw: $grid,
            allowedKeys: static::getAllowedComponentKeys($customerIds),
        );
    }
}
