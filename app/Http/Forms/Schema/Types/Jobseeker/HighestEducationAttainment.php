<?php

declare(strict_types=1);

namespace App\Http\Forms\Schema\Types\Jobseeker;

use App\Http\Forms\Schema\Types\BaseSelectPicklistItem;

class HighestEducationAttainment extends BaseSelectPicklistItem
{
    public static function make()
    {
        return parent::build('education_attainment_id')
            ->relationship('education_attainment')
            ->label('Highest education attainment')
            ->picklistIdentifier('education-attainment')
            ->required()
            ->get();
    }
}
