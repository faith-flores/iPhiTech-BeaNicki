<?php

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;

class Terms
{

    public static function make() : Field
    {
        return Checkbox::make('terms')
            ->label('I accept the BeaNicki Terms of Service and Privacy Policy')
            ->declined(false)
            ->required()
        ;
    }
}
