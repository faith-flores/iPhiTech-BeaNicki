<?php

namespace App\Filament\JobseekerPanel\Resources\JobseekerResource\Pages;

use App\Filament\JobseekerPanel\Resources\JobseekerResource;
use App\Filament\Services\JobseekerResourceService;
use App\Filament\Services\SkillResourceService;
use App\Http\Forms\Schema\JobseekerProfileWizardSchema;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\HasWizard;
use Illuminate\Database\Eloquent\Model;

class JobseekerProfileWizard extends EditRecord
{
    use HasWizard;

    /**
     * @var SkillResourceService
     */
    private SkillResourceService $skillService;

    protected static string $resource = JobseekerResource::class;

    public function __construct()
    {
        $this->skillService = app(SkillResourceService::class);
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

        /**
         * TODO: Error message
         */
    }
}
