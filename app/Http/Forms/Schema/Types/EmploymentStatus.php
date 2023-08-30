<?php

declare(strict_types=1);

namespace App\Http\Forms\Schema\Types;

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
