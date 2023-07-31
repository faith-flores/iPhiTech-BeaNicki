<?php

namespace App\Filament\Resources\PicklistResource\Pages;

use App\Filament\Resources\PicklistResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPicklist extends EditRecord
{
    protected static string $resource = PicklistResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
