<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomComponentResource\Pages\CreateCustomComponent;
use App\Filament\Admin\Resources\CustomComponentResource\Pages\EditCustomComponent;
use App\Filament\Admin\Resources\CustomComponentResource\Pages\ListCustomComponents;
use App\Filament\Admin\Resources\CustomComponentResource\Pages\ViewCustomComponent;
use App\Models\CustomComponent;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use TimoDeWinter\FilamentMonacoEditor\Filament\Forms\Components\MonacoEditor;
use UnitEnum;

class CustomComponentResource extends Resource
{
    protected static ?string $model = CustomComponent::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::PuzzlePiece;

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
        return $schema->columns(1)->components([
            Section::make('Details')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Select::make('customer_id')
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('Customer'),
                ]),
            Section::make('Blade Template')
                ->columnSpanFull()
                ->schema([
                    MonacoEditor::make('blade')
                        ->language('blade')
                        ->height('280px')
                        ->columnSpanFull(),
                ]),
            Section::make('PHP')
                ->columnSpanFull()
                ->schema([
                    MonacoEditor::make('php')
                        ->language('php')
                        ->height('280px')
                        ->columnSpanFull(),
                ]),
            Section::make('SCSS')
                ->columnSpanFull()
                ->collapsible()
                ->schema([
                    MonacoEditor::make('scss')
                        ->language('scss')
                        ->height('280px')
                        ->helperText('SCSS syntax is validated by the Monaco field for this sprint.')
                        ->columnSpanFull(),
                ]),
            Section::make('JavaScript')
                ->columnSpanFull()
                ->collapsible()
                ->schema([
                    MonacoEditor::make('js')
                        ->language('javascript')
                        ->height('280px')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('title'),
                    TextEntry::make('customer.name')->label('Customer'),
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
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
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
            'index' => ListCustomComponents::route('/'),
            'create' => CreateCustomComponent::route('/create'),
            'view' => ViewCustomComponent::route('/{record}'),
            'edit' => EditCustomComponent::route('/{record}/edit'),
        ];
    }
}
