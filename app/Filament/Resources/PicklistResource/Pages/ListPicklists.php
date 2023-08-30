<?php

declare(strict_types=1);

namespace App\Filament\Resources\PicklistResource\Pages;

use App\Filament\Resources\PicklistResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPicklists extends ListRecords
{
    protected static string $resource = PicklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
