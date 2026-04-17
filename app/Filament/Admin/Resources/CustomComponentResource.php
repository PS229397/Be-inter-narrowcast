<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Forms\Components\CustomComponentPreviewField;
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
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
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
            Section::make('Workspace')
                ->columnSpanFull()
                ->columns(12)
                ->schema([
                    CustomComponentPreviewField::make('preview')
                        ->hiddenLabel()
                        ->columnSpan([
                            'default' => 12,
                            'xl' => 8,
                        ]),
                    Tabs::make('Code')
                        ->columnSpan([
                            'default' => 12,
                            'xl' => 4,
                        ])
                        ->persistTabInQueryString('custom-component-code-tab')
                        ->tabs([
                            Tab::make('Blade')
                                ->schema([
                                    MonacoEditor::make('blade')
                                        ->language('html')
                                        ->height('380px')
                                        ->live(debounce: 300)
                                        ->columnSpanFull(),
                                ]),
                            Tab::make('PHP')
                                ->schema([
                                    MonacoEditor::make('php')
                                        ->language('php')
                                        ->height('380px')
                                        ->live(debounce: 300)
                                        ->columnSpanFull(),
                                ]),
                            Tab::make('JavaScript')
                                ->schema([
                                    MonacoEditor::make('js')
                                        ->language('javascript')
                                        ->height('380px')
                                        ->live(debounce: 300)
                                        ->columnSpanFull(),
                                ]),
                            Tab::make('SCSS')
                                ->schema([
                                    MonacoEditor::make('scss')
                                        ->language('scss')
                                        ->height('380px')
                                        ->helperText('SCSS is applied directly in preview for rapid feedback.')
                                        ->live(debounce: 300)
                                        ->columnSpanFull(),
                                ]),
                        ]),
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
