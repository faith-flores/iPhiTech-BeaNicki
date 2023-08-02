<?php
namespace App\Http\Forms\Schema;

use App\Models\Billing;
use App\Models\Profile;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ProfileBillingSchema
{

    public static function make()
    {
        return Group::make()
            ->schema([
                TextInput::make('company_name')
                    ->required(),
                TextInput::make('invoice_name')
                    ->label('Name on Invoice')
                    ->required(),
                TextInput::make('email')
                    ->required(),
                AddressSchema::make()->model(Billing::class),
                Select::make('billing_type')
                    ->options([
                        'text' => __('Prepaid Client'),
                        'value' => 0,
                    ],
                    [
                        'text' => __('Postpay Client'),
                        'value' => 1,
                    ])
            ])->relationship('billing')->model(Profile::class);
    }
}
