<?php

namespace App\Filament\JobseekerPanel\Resources\JobResource\Pages;

use App\Filament\JobseekerPanel\Resources\JobResource;
use Filament\Resources\Pages\ViewRecord;

class ViewJob extends ViewRecord
{
    protected static string $resource = JobResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $this->heading = $this->record->title;
        static::$breadcrumb = $this->record->title;
    }

    protected function authorizeAccess(): void
    {
        static::authorizeResourceAccess();

        abort_unless(static::getResource()::canView($this->getRecord()), 403);
    }
}
