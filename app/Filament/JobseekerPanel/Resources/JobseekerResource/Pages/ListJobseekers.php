<?php

namespace App\Filament\JobseekerPanel\Resources\JobseekerResource\Pages;

use App\Filament\JobseekerPanel\Resources\JobseekerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobseekers extends ListRecords
{
    protected static string $resource = JobseekerResource::class;

    protected static bool $isDiscovered = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
