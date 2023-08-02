<?php

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;

class LastName
{

    public static function make() : Field
    {
        return TextInput::make('last_name')
            ->required()
            ->label(__( 'Last Name' ))
            ->maxLength(100)
        ;
    }
}
