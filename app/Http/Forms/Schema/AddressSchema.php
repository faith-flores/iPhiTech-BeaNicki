<?php
namespace App\Http\Forms\Schema;

use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;

class AddressSchema
{

    public static function make()
    {
        return Group::make()
            ->schema([
                TextInput::make('address_line_1')->required(),
                TextInput::make('address_line_2'),

                Grid::make(2)
                    ->schema([
                        TextInput::make('street')->columnSpan(1)->required(),
                        TextInput::make('city')->columnSpan(1)->required(),
                        TextInput::make('zip_code')->columnSpan(1)->required(),
                        TextInput::make('province')->columnSpan(1)->required(),
                        TextInput::make('address_type')->visible(! auth()->user()->hasRole(User::USER_ROLE_JOBSEEKER))->columnSpan(2),
                    ])
            ])->relationship('address');
    }
}
