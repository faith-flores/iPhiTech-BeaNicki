<?php

namespace App\Http\Forms\Schema;

use App\Models\User;
use Filament\Forms\Components\Component;
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
                AddressSchema::make(),
                TextInput::make('company_phone')
                    ->required()
                    ->tel()
                    ->telRegex('/^(09|\+639)\d{9}$/'),
                TextInput::make('web_url')
                    ->label('Website Address')
                    ->url(),
            ])
            ->relationship('account')
            ->model(User::class);
    }
}
