<?php

namespace App\Filament\App\Resources\Slideshows\Tables;

use App\Filament\App\Resources\Slideshows\SlideshowResource;
use App\Models\Slideshow;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SlideshowsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('slides_count')
                    ->counts('slides')
                    ->label('Slides')
                    ->sortable(),
                TextColumn::make('locations_count')
                    ->counts('locations')
                    ->label('Locations')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->recordActions([
                Action::make('open_display')
                    ->label('Open Display')
                    ->icon(Heroicon::ArrowTopRightOnSquare)
                    ->color('gray')
                    ->url(fn (Slideshow $record): string => SlideshowResource::getDisplayUrl($record))
                    ->openUrlInNewTab(),
                Action::make('toggle_active')
                    ->label(fn (Slideshow $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                    ->icon(fn (Slideshow $record): Heroicon => $record->is_active ? Heroicon::PauseCircle : Heroicon::PlayCircle)
                    ->color(fn (Slideshow $record): string => $record->is_active ? 'warning' : 'success')
                    ->action(function (Slideshow $record): void {
                        $isActive = ! $record->is_active;

                        $record->update(['is_active' => $isActive]);

                        Notification::make()
                            ->title($isActive ? 'Slideshow activated' : 'Slideshow deactivated')
                            ->success()
                            ->send();
                    }),
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
