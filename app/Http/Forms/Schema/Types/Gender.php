<?php

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class Gender extends BaseSelectPicklistItem
{

    public static function make()
    {
        return self::build('gender_id')
            ->relationship('gender')
            ->label('Gender')
            ->picklistIdentifier('gender')
            ->required();
    }
}
