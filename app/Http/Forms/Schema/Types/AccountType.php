<?php

declare(strict_types=1);

namespace App\Http\Forms\Schema\Types;

use App\Models\Account;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Radio;

class AccountType
{
    public static function make() : Field
    {
        return Radio::make('account_type')
            ->options([
                Account::ACCOUNT_TYPE_PERSONAL => 'Personal',
                Account::ACCOUNT_TYPE_BUSINESS => 'Business / Agency',
            ])
            ->in([Account::ACCOUNT_TYPE_PERSONAL, Account::ACCOUNT_TYPE_BUSINESS])
            ->model(Account::class)
            ->live()
            ->required();
    }
}
