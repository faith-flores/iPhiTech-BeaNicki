<?php

namespace App\Http\Forms\Schema;

use App\Http\Forms\Schema\Types\Email;
use App\Http\Forms\Schema\Types\FirstName;
use App\Http\Forms\Schema\Types\LastName;
use App\Http\Forms\Schema\Types\Phone;
use App\Models\Account;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;

class AccountCompanySchema
{

    /**
     * @return Component
     */
    public static function make() : Component
    {
        return Group::make()
            ->schema([
                TextInput::make('company_name')
                    ->required(),
                AddressSchema::make()->model(Account::class),
                TextInput::make('company_phone')
                    ->required()
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                TextInput::make('web_url')
                    ->label('Website Address')
                    ->url(),
            ]);
    }
}