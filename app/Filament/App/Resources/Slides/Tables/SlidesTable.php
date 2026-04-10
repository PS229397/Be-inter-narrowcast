<?php

namespace App\Filament\App\Resources\Slides\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('layout.title')
                    ->sortable()
                    ->label('Layout'),
                TextColumn::make('layout.orientation')
                    ->badge()
                    ->label('Orientation'),
                TextColumn::make('category.title')
                    ->sortable()
                    ->label('Category')
                    ->placeholder('—'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('duration_in_seconds')
                    ->suffix('s')
                    ->label('Duration')
                    ->sortable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
                SelectFilter::make('layout')
                    ->relationship(
                        'layout',
                        'title',
                        fn ($query) => $query->availableToCustomer(auth()->user()?->customer_id)
                    )
                    ->searchable()
                    ->preload(),
                SelectFilter::make('category')
                    ->relationship(
                        'category',
                        'title',
                        fn ($query) => $query->where('customer_id', auth()->user()?->customer_id)
                    )
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
}
