<?php

declare(strict_types=1);

namespace App\Filament\JobseekerPanel\Resources\JobResource\Pages;

use App\Filament\JobseekerPanel\Resources\JobResource;
use Filament\Resources\Pages\ListRecords;

class ListAvailableJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

    protected static ?string $title = 'Available Jobs';

    protected static ?string $navigationLabel = 'Available Jobs';

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
