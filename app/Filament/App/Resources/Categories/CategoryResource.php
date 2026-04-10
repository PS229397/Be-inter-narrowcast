<?php

namespace App\Filament\App\Resources\Categories;

use App\Filament\App\Resources\Categories\Pages\CreateCategory;
use App\Filament\App\Resources\Categories\Pages\EditCategory;
use App\Filament\App\Resources\Categories\Pages\ListCategories;
use App\Filament\App\Resources\Categories\Pages\ViewCategory;
use App\Filament\App\Resources\Categories\Schemas\CategoryForm;
use App\Filament\App\Resources\Categories\Schemas\CategoryInfolist;
use App\Filament\App\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Tag;

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
        return CategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'view' => ViewCategory::route('/{record}'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('customer_id', auth()->user()?->customer_id);
    }
}
