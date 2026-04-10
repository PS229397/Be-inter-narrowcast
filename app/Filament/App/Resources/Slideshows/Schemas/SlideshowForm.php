<?php

namespace App\Filament\App\Resources\Slideshows\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideshowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                Section::make('Slideshow Builder')
                    ->columnSpan(3)
                    ->schema([
                        Placeholder::make('slideshow_builder_placeholder')
                            ->label('Slideshow Builder')
                            ->content('Slides are attached after creation from the relation manager on the slideshow detail page. Orientation validation and richer playlist tooling are intentionally deferred for this sprint.'),
                    ]),

                Section::make('Meta')
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Toggle::make('is_active')
                            ->default(false)
                            ->label('Active'),

                        Select::make('locations')
                            ->relationship(
                                'locations',
                                'name',
                                fn ($query) => $query->where('customer_id', auth()->user()?->customer_id)
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Linked Locations')
                            ->helperText('Only locations belonging to your customer are available.'),

                        Placeholder::make('display_url')
                            ->label('Display URL')
                            ->content(fn ($record) => $record
                                ? \App\Filament\App\Resources\Slideshows\SlideshowResource::getDisplayUrl($record)
                                : '—'
                            )
                            ->hiddenOn('create'),
                    ]),
            ]);
    }
}
