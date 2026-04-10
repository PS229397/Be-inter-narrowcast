<?php

namespace App\Filament\App\Resources\Locations\Tables;

use App\Enums\Orientation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('orientation')
                    ->badge()
                    ->sortable(),
                TextColumn::make('slideshows_count')
                    ->counts('slideshows')
                    ->label('Slideshows')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('orientation')
                    ->options(Orientation::class),
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
}
