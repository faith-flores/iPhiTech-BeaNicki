<?php

namespace App\Filament\JobseekerPanel\Resources\JobseekerResource\Pages;

use App\Filament\JobseekerPanel\Resources\JobseekerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class EditJobseeker extends ViewRecord
{
    protected static string $resource = JobseekerResource::class;

    protected static ?string $title = "Profile Details";

    protected function getHeaderActions(): array
    {

        return [
            Actions\DeleteAction::make(),
        ];
    }
}
