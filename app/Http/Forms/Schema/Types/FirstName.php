<?php

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;

class FirstName
{

    public static function make() : Field
    {
        return TextInput::make('first_name')
            ->required()
            ->label(__( 'First Name' ))
            ->maxLength(100)
        ;
    }
}
