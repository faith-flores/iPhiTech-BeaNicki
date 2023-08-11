<?php

namespace App\Filament\JobseekerPanel\Resources\JobseekerResource\Pages;

use App\Filament\JobseekerPanel\Resources\JobseekerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobseeker extends EditRecord
{
    protected static string $resource = JobseekerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
