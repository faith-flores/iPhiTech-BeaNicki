<?php

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class HighestEducationAttainment
{

    public static function make() : Field
    {
        return Select::make('education_attainment_id')
            ->required()
            ->label("Highest education attainment")
            ->relationship('education_attainment', 'label', (function (Builder $query) {
                $query->ofPicklistIdentifier('education-attainment');
            }))
        ;
    }
}
