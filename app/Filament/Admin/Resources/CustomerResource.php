<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Admin\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Admin\Resources\CustomerResource\Pages\ListCustomers;
use App\Filament\Admin\Resources\CustomerResource\Pages\ViewCustomer;
use App\Filament\Admin\Resources\CustomerResource\RelationManagers\UsersRelationManager;
use App\Models\Customer;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Customers';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Company Details')
                ->columns(1)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g. BE-Interactive'),
                ]),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Company Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->label('Created'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users')
                    ->sortable(),
                TextColumn::make('layouts_count')
                    ->counts('layouts')
                    ->label('Layouts')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                static::getImpersonateAction(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getImpersonateAction(): Action
    {
        return Action::make('impersonate')
            ->label('Impersonate')
            ->icon(Heroicon::OutlinedArrowRightOnRectangle)
            ->color('warning')
            ->requiresConfirmation()
            ->visible(fn (Customer $record): bool => $record->users()->exists())
            ->action(function (Customer $record, $livewire): void {
                $user = $record->users()->oldest('id')->first();

                if (! $user) {
                    Notification::make()
                        ->title('This customer does not have a user to impersonate yet.')
                        ->danger()
                        ->send();

                    return;
                }

                session(['admin_impersonator_id' => Auth::guard('admin')->id()]);
                Auth::guard('web')->login($user);

                Notification::make()
                    ->title("Impersonating {$record->name}")
                    ->warning()
                    ->send();

                $livewire->redirect(Filament::getPanel('app')->getUrl(), navigate: true);
            });
    }
}
