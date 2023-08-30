<?php

declare(strict_types=1);

namespace App\Http\Forms\Schema\Types\Jobseeker;

use App\Http\Forms\Schema\Types\BaseSelectPicklistItem;

class Gender extends BaseSelectPicklistItem
{
    public static function make()
    {
        return parent::build('gender_id')
            ->relationship('gender')
            ->label('Gender')
            ->picklistIdentifier('gender')
            ->required()
            ->get();
    }
}
