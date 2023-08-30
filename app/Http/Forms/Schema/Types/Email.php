<?php

declare(strict_types=1);

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;

class Email
{
    public static function make() : Field
    {
        return TextInput::make('email')
            ->required()
            ->email();
    }
}
