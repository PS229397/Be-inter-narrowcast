<?php

namespace App\Filament\Resources\CustomComponents\Schemas;

use Filament\Schemas\Schema;

class CustomComponentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
