<?php

declare(strict_types=1);

namespace App\Filament\JobseekerPanel\Resources\JobseekerResource\Pages;

use App\Filament\JobseekerPanel\Resources\JobseekerResource;
use App\Filament\Services\JobseekerResourceService;
use App\Filament\Services\SkillResourceService;
use App\Http\Forms\Schema\JobseekerProfileWizardSchema;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\HasWizard;

class JobseekerProfileWizard extends EditRecord
{
    use HasWizard;

    protected static ?string $title = 'Edit Your Profile';

    private SkillResourceService $skillService;

    protected static string $resource = JobseekerResource::class;

    public function __construct()
    {
        $this->skillService = app(SkillResourceService::class);
    }

    protected function authorizeAccess(): void
    {
        abort_unless(static::getResource()::canEdit($this->getRecord()), 403);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['address'] = $this->getRecord()?->address?->toArray();
        $skills = $this->skillService->formatDataForDisplay($this->getRecord());

        $data = [...$data, ...$skills];

        return $data;
    }

    protected function getSteps(): array
    {
        return JobseekerProfileWizardSchema::make();
    }

    protected function afterSave()
    {
        $data = app(SkillResourceService::class)->formatFormSkillsData($this->data);

        if (app(JobseekerResourceService::class)->editProfile($this->getRecord(), $data)) {
            $this->redirect(route('filament.jobseekers.pages.dashboard'));
        }
    }
}
