<?php

namespace App\Http\Forms\Schema\Types;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class EmploymentStatus extends BaseSelectPicklistItem
{

    public static function make()
    {
        return parent::build('employment_status_id')
            ->relationship('employment_status')
            ->label('Employment Status')
            ->picklistIdentifier('employment-status')
            ->required()
            ->get();
    }
}
