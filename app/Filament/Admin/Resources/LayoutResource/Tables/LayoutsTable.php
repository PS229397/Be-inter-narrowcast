<?php

namespace App\Filament\Admin\Resources\LayoutResource\Tables;

use App\Enums\Orientation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LayoutsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('orientation')
                    ->badge()
                    ->sortable(),
                TextColumn::make('customers_count')
                    ->counts('customers')
                    ->label('Customers')
                    ->sortable()
                    ->formatStateUsing(fn (string|int|null $state): string => (int) $state === 0 ? 'All customers' : (string) $state),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('orientation')
                    ->options(Orientation::class),
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
}
