<?php

namespace App\Http\Forms\Schema\Types;

class DesiredSalary extends BaseSelectPicklistItem
{

    public static function make()
    {
        return parent::build('desired_salary_id')
            ->relationship('desired_salary')
            ->label('Desired Salary')
            ->picklistIdentifier('desired-salary')
            ->required()
            ->get();
    }
}
