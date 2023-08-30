<?php

namespace App\Http\Forms\Schema;

use App\Http\Forms\Schema\Types\DateOfBirth;
use App\Http\Forms\Schema\Types\Email;
use App\Http\Forms\Schema\Types\FirstName;
use App\Http\Forms\Schema\Types\LastName;
use App\Http\Forms\Schema\Types\Phone;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;

class ProfileSchema
{

    /**
     * @return Component
     */
    public static function make() : Component
    {
        return Group::make()
            ->schema([
                FirstName::make(),
                LastName::make(),
                Phone::make(),
                Email::make(),
                DateOfBirth::make(),
            ]);
    }
}
