<?php

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;

class InvoiceName
{

    public static function make() : Field
    {
        return TextInput::make('invoice_name')
            ->required()
            ->label(__( 'Name on Invoice' ))
            ->maxLength(100)
        ;
    }
}
