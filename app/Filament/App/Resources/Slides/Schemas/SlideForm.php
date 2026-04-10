<?php

namespace App\Filament\App\Resources\Slides\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use App\Models\Layout;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                Section::make('Slide Content')
                    ->columnSpan(3)
                    ->schema([
                        Select::make('layout_id')
                            ->relationship(
                                'layout',
                                'title',
                                fn ($query) => $query->availableToCustomer(auth()->user()?->customer_id)
                            )
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(function (Layout $record): string {
                                $availability = $record->customers()->exists() ? 'Assigned' : 'All customers';

                                return "{$record->title} ({$record->orientation->getLabel()}, {$availability})";
                            })
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('slide_content', []))
                            ->helperText('Layouts assigned to your customer, plus global layouts, are available here.')
                            ->label('Layout')
                            ->required(),

                        Placeholder::make('slide_content_placeholder')
                            ->label('Slide Editor')
                            ->content('Slide canvas editing is intentionally deferred for this sprint. You can already link the slide to a layout and manage its scheduling metadata.'),

                        Hidden::make('slide_content')
                            ->default([]),
                    ]),

                Section::make('Meta')
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Select::make('category_id')
                            ->relationship(
                                'category',
                                'title',
                                fn ($query) => $query->where('customer_id', auth()->user()?->customer_id)
                            )
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->label('Category (optional)'),

                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Active'),

                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->nullable(),

                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->nullable()
                            ->after('start_date'),

                        TextInput::make('duration_in_seconds')
                            ->integer()
                            ->default(10)
                            ->minValue(1)
                            ->suffix('sec')
                            ->label('Duration')
                            ->required(),
                    ]),
            ]);
    }
}
