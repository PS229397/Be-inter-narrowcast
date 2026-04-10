<?php

namespace App\Filament\App\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }
}
