<?php

namespace App\Filament\App\Resources\Slideshows\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\Hidden;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SlidesRelationManager extends RelationManager
{
    protected static string $relationship = 'slides';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('pivot.sort_order')
            ->reorderable('pivot.sort_order')
            ->columns([
                TextColumn::make('pivot.sort_order')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('layout.orientation')
                    ->label('Orientation')
                    ->badge(),
                TextColumn::make('layout.title')
                    ->label('Layout')
                    ->sortable(),
                TextColumn::make('duration_in_seconds')
                    ->suffix('s')
                    ->label('Duration'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['title'])
                    ->recordSelectOptionsQuery(fn ($query) => $query
                        ->where('customer_id', auth()->user()?->customer_id)
                        ->orderBy('title'))
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Hidden::make('sort_order')
                            ->default(fn (): int => $this->getOwnerRecord()->slides()->count() + 1),
                    ]),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
