<?php

namespace App\Filament\App\Resources\Slideshows;

use App\Filament\App\Resources\Slideshows\Pages\CreateSlideshow;
use App\Filament\App\Resources\Slideshows\Pages\EditSlideshow;
use App\Filament\App\Resources\Slideshows\Pages\ListSlideshows;
use App\Filament\App\Resources\Slideshows\Pages\ViewSlideshow;
use App\Filament\App\Resources\Slideshows\RelationManagers\SlidesRelationManager;
use App\Filament\App\Resources\Slideshows\Schemas\SlideshowForm;
use App\Filament\App\Resources\Slideshows\Schemas\SlideshowInfolist;
use App\Filament\App\Resources\Slideshows\Tables\SlideshowsTable;
use App\Models\Slideshow;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class SlideshowResource extends Resource
{
    protected static ?string $model = Slideshow::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::PlayCircle;

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Screens';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function form(Schema $schema): Schema
    {
        return SlideshowForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SlideshowInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlideshowsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SlidesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSlideshows::route('/'),
            'create' => CreateSlideshow::route('/create'),
            'view' => ViewSlideshow::route('/{record}'),
            'edit' => EditSlideshow::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('customer_id', auth()->user()?->customer_id);
    }

    public static function getDisplayUrl(Slideshow $record): string
    {
        return url('/display/' . $record->customer_id . '/' . $record->id);
    }
}
