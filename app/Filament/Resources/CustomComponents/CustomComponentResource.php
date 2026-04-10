<?php

namespace App\Filament\Resources\CustomComponents;

use App\Filament\Resources\CustomComponents\Pages\CreateCustomComponent;
use App\Filament\Resources\CustomComponents\Pages\EditCustomComponent;
use App\Filament\Resources\CustomComponents\Pages\ListCustomComponents;
use App\Filament\Resources\CustomComponents\Pages\ViewCustomComponent;
use App\Filament\Resources\CustomComponents\Schemas\CustomComponentForm;
use App\Filament\Resources\CustomComponents\Schemas\CustomComponentInfolist;
use App\Filament\Resources\CustomComponents\Tables\CustomComponentsTable;
use App\Models\CustomComponent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomComponentResource extends Resource
{
    protected static ?string $model = CustomComponent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CustomComponentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomComponentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomComponentsTable::configure($table);
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
            'index' => ListCustomComponents::route('/'),
            'create' => CreateCustomComponent::route('/create'),
            'view' => ViewCustomComponent::route('/{record}'),
            'edit' => EditCustomComponent::route('/{record}/edit'),
        ];
    }
}
