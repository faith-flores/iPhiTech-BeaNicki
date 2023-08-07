<?php

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;

class Phone
{

    public static function make() : Field
    {
        return TextInput::make('phone')
            ->required()
            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
        ;
    }
}
