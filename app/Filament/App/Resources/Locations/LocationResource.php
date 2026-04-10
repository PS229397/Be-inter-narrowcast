<?php

namespace App\Filament\App\Resources\Locations;

use App\Filament\App\Resources\Locations\Pages\CreateLocation;
use App\Filament\App\Resources\Locations\Pages\EditLocation;
use App\Filament\App\Resources\Locations\Pages\ListLocations;
use App\Filament\App\Resources\Locations\Pages\ViewLocation;
use App\Filament\App\Resources\Locations\Schemas\LocationForm;
use App\Filament\App\Resources\Locations\Schemas\LocationInfolist;
use App\Filament\App\Resources\Locations\Tables\LocationsTable;
use App\Models\Location;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ComputerDesktop;

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Screens';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug'];
    }

    public static function form(Schema $schema): Schema
    {
        return LocationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LocationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocations::route('/'),
            'create' => CreateLocation::route('/create'),
            'view' => ViewLocation::route('/{record}'),
            'edit' => EditLocation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('customer_id', auth()->user()?->customer_id);
    }
}
