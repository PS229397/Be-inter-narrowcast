<?php

namespace App\Filament\Admin\Resources\CustomerResource\RelationManagers;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->required()
                ->email()
                ->maxLength(255)
                ->unique(table: 'users', column: 'email', ignoreRecord: true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Invited'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Invite User')
                    ->using(function (array $data): User {
                        $data['customer_id'] = $this->getOwnerRecord()->getKey();
                        $data['password'] = Hash::make(Str::random(40));

                        $user = User::create($data);

                        $this->dispatchInvitation($user);

                        return $user;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function dispatchInvitation(User $user): void
    {
        $inviteAction = '\TimoDeWinter\UserManagement\Actions\InviteUser';

        if (class_exists($inviteAction)) {
            app($inviteAction)->handle($user);
        }
    }
}
