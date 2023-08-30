<?php

declare(strict_types=1);

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;

class DateOfBirth
{
    public static function make() : Field
    {
        return DatePicker::make('date_of_birth')
            ->label('Date of Birth')
            ->required();
    }
}
