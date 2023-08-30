<?php

namespace App\Http\Forms\Schema\Types;

class HoursToWork extends BaseSelectPicklistItem
{

    public static function make()
    {
        return parent::build('hours_to_work_id')
            ->relationship('hours_to_work')
            ->label('Hours to work in a week')
            ->picklistIdentifier('hours-to-work')
            ->required()
            ->get();
    }
}
