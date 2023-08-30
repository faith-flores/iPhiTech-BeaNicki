<?php

declare(strict_types=1);

namespace App\Filament\Resources\PicklistResource\Pages;

use App\Filament\Resources\PicklistResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePicklist extends CreateRecord
{
    protected static string $resource = PicklistResource::class;
}
