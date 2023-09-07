<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use Closure;
use App\Models\Jobseeker;
use Filament\Pages\Actions;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\Alignment;
use App\Http\Forms\Schema\Types\Email;
use App\Http\Forms\Schema\Types\Phone;
use App\Http\Forms\Schema\SkillsSchema;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Tabs;
use App\Filament\Resources\UserResource;
use App\Http\Forms\Schema\AddressSchema;
use App\Http\Forms\Schema\Types\Website;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Http\Forms\Schema\Types\LastName;
use App\Http\Forms\Schema\Types\FirstName;
use Filament\Infolists\Components\Section;
use App\Http\Forms\Schema\Types\DateOfBirth;
use App\Http\Forms\Schema\Types\HoursToWork;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use App\Http\Forms\Schema\Types\DesiredSalary;
use App\Filament\Services\SkillResourceService;
use Filament\Forms\Components\Tabs as FormTabs;
use App\Http\Forms\Schema\Types\EmploymentStatus;
use App\Http\Forms\Schema\Types\Jobseeker\Gender;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use App\Http\Forms\Schema\Types\Jobseeker\HighestEducationAttainment;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    private function tabAction(string $id, array | Closure | null $form, string $modalWidth = 'md'): ViewEntry
    {
        return ViewEntry::make($id)
            ->extraAttributes([
                'class' => 'absolute top-3 right-3',
                'action' => $id,
            ], true)
            ->view('filament.infolists.tabs.edit-action')
            ->alignRight()
            ->alignEnd()
            ->action(
                Action::make($id)
                    ->hiddenLabel()
                    ->icon('heroicon-o-pencil-square')
                    ->fillForm(function (SkillResourceService $service): array {
                        $data = $this->getRecord()->toArray();
                        $skills = $service->formatDataForDisplay($this->getRecord());

                        return [...$data, ...$skills];
                    })
                    ->slideOver()
                    ->modalWidth($modalWidth)
                    ->form($form)
                    ->afterFormValidated(function (array $data, Jobseeker $jobseeker) {
                        $data = app(SkillResourceService::class)->formatFormSkillsData($data);

                        if (app(JobseekerResourceService::class)->editProfile($this->getRecord(), $data)) {
                            Notification::make()
                                ->success()
                                ->title('Edit details successfully')
                                ->send();
                        } else {
                            Notification::make()
                                ->success()
                                ->title('Edit details successfully')
                                ->send();
                        }
                    }),
            );
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Section::make(function ($record) {
                    if ($record instanceof Jobseeker) {
                        return $record->display_name;
                    }
                })
                    ->inlineLabel()
                    ->columnSpan(3)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('address.province')->label('Province'),
                                TextEntry::make('created_at')
                                    ->label('Joining Date')
                                    ->date('F d, Y'),
                                TextEntry::make('average_rating')->label('Avg. Rating')->default(999),
                                TextEntry::make('jobs_applied')->label('Jobs Applied')->default(999),
                                TextEntry::make('skills.label')->badge()->limitList(10),
                            ]),
                    ]),
            ]);
    }
}
