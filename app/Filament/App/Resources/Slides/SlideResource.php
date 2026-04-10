<?php

namespace App\Filament\App\Resources\Slides;

use App\Filament\App\Resources\Slides\Pages\CreateSlide;
use App\Filament\App\Resources\Slides\Pages\EditSlide;
use App\Filament\App\Resources\Slides\Pages\ListSlides;
use App\Filament\App\Resources\Slides\Pages\ViewSlide;
use App\Filament\App\Resources\Slides\Schemas\SlideForm;
use App\Filament\App\Resources\Slides\Schemas\SlideInfolist;
use App\Filament\App\Resources\Slides\Tables\SlidesTable;
use App\Models\Slide;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class SlideResource extends Resource
{
    protected static ?string $model = Slide::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Photo;

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Content';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function form(Schema $schema): Schema
    {
        return SlideForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SlideInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlidesTable::configure($table);
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
            'index' => ListSlides::route('/'),
            'create' => CreateSlide::route('/create'),
            'view' => ViewSlide::route('/{record}'),
            'edit' => EditSlide::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('customer_id', auth()->user()?->customer_id);
    }
}
