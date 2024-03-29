<?php

declare(strict_types=1);

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;

class Phone
{
    public static function make() : Field
    {
        return TextInput::make('phone_number')
            ->required()
            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
            ->tel()
            ->maxLength(13);
    }
}
