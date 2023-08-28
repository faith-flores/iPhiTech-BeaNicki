<?php

namespace App\Filament\JobseekerPanel\Resources\JobseekerResource\Pages;

use App\Filament\JobseekerPanel\Resources\JobseekerResource;
use App\Filament\Services\JobseekerResourceService;
use App\Filament\Services\SkillResourceService;
use App\Forms\Components\StarRating;
use App\Http\Forms\Schema\AddressSchema;
use App\Http\Forms\Schema\SkillsSchema;
use App\Http\Forms\Schema\Types\DateOfBirth;
use App\Http\Forms\Schema\Types\DesiredSalary;
use App\Http\Forms\Schema\Types\Email;
use App\Http\Forms\Schema\Types\EmploymentStatus;
use App\Http\Forms\Schema\Types\FirstName;
use App\Http\Forms\Schema\Types\HoursToWork;
use App\Http\Forms\Schema\Types\Jobseeker\Gender;
use App\Http\Forms\Schema\Types\Jobseeker\HighestEducationAttainment;
use App\Http\Forms\Schema\Types\LastName;
use App\Http\Forms\Schema\Types\Phone;
use App\Http\Forms\Schema\Types\Website;
use App\Models\Jobseeker;
use Closure;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs as ComponentsTabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Arr;

class ViewJobseekerProfile extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithRecord;

    protected static string $resource = JobseekerResource::class;

    protected static string $view = 'filament.jobseeker-panel.resources.jobseeker-resource.pages.view-jobseeker-profile';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->authorizeAccess();
    }

    protected function authorizeAccess(): void
    {
        abort_unless(static::getResource()::canView($this->getRecord()), 403);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Grid::make(4)
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('cat')
                            ->collection('avatars')
                            ->hiddenLabel()
                            ->size('227px')
                            ->default(fn () => asset('/storage/logo/avatar.svg'))
                            ->circular()->columnSpan(1)
                            ->action(
                                Action::make('editProfilePicture')
                                    ->modalWidth('sm')
                                    ->modalSubmitActionLabel('Upload')
                                    ->modalFooterActionsAlignment(Alignment::Center)
                                    ->form([
                                        SpatieMediaLibraryFileUpload::make('media')
                                            ->extraAttributes([
                                                'class' => 'justify-center text-center'
                                            ])
                                            ->alignment(Alignment::Center)
                                            ->hiddenLabel()
                                            ->alignCenter()
                                            ->columnSpanFull()
                                            ->disk('local')
                                            ->avatar()
                                            ->collection('avatars')
                                    ])
                            )
                        ,
                        Section::make(fn (Jobseeker $record) => $record->display_name)
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
                                        TextEntry::make('skills.label')->badge()->limitList(10)
                                    ])
                        ]),
                ]),
                Tabs::make('')
                    ->extraAttributes([
                        'class' => 'fi-tabs-jobseeker'
                    ], true)
                    ->columnSpan('full')
                    ->persistTabInQueryString()
                    ->tabs([
                        Tabs\Tab::make('Personal Details')
                            ->inlineLabel()
                            ->schema([
                                Grid::make(2)
                                    ->registerActions([
                                    ])
                                    ->schema([
                                        TextEntry::make('display_name')->label('Full Name'),
                                        TextEntry::make('nickname'),
                                        TextEntry::make('date_of_birth')->date('F d, Y'),
                                        TextEntry::make('gender.label'),
                                        TextEntry::make('phone_number'),
                                        TextEntry::make('email'),
                                ])->extraAttributes(['class' => 'pb-6 border-b border-gray-400'], true),
                                Grid::make(2)
                                    ->schema([
                                        Group::make([
                                            TextEntry::make('address_line_1'),
                                            TextEntry::make('address_line_2'),
                                            TextEntry::make('street'),
                                            TextEntry::make('zip_code'),
                                            TextEntry::make('city'),
                                            TextEntry::make('province'),
                                        ])
                                        ->columnSpanFull()
                                        ->columns(2)
                                        ->relationship('address')
                                ]),
                                $this->tabAction(
                                    'editDetails',
                                    [
                                        FirstName::make(),
                                        LastName::make(),
                                        Phone::make(),
                                        Email::make()
                                            ->unique('jobseekers', null, null, true),
                                        DateOfBirth::make(),
                                        Gender::make(),
                                        AddressSchema::make()
                                    ],
                                ),
                        ]),
                        Tabs\Tab::make('Work Details')
                            ->inlineLabel()
                            ->schema([
                                Grid::make(2)
                                ->schema([
                                    TextEntry::make('job_title')->alignStart(),
                                    TextEntry::make('skills_summary')->alignStart(),
                                    TextEntry::make('experience')->alignStart(),
                                    TextEntry::make('website_url')->alignStart(),
                                    TextEntry::make('education_attainment.label')
                                        ->label('Highest Educational Attainment')->alignStart(),
                                    TextEntry::make('website_url'),
                                    TextEntry::make('hours_to_work.label'),
                                    TextEntry::make('desired_salary.label'),
                                    TextEntry::make('employment_status.label'),
                                ]),
                                $this->tabAction(
                                    'editWorkDetails',
                                    [
                                        TextInput::make('job_title')->required(),
                                        Textarea::make('skills_summary')->required(),
                                        HighestEducationAttainment::make(),
                                        DesiredSalary::make(),
                                        TextInput::make('experience')->required(),
                                        EmploymentStatus::make(),
                                        HoursToWork::make(),
                                        Website::make(),
                                    ]
                                ),
                        ]),
                        Tabs\Tab::make('Skills')
                            ->schema([
                                RepeatableEntry::make('skills')
                                    ->grid(3)
                                    ->hiddenLabel()
                                    ->columns(2)
                                    ->schema([
                                        TextEntry::make('label')
                                            ->badge()
                                            ->hiddenLabel(),
                                        Group::make(fn ($state) => self::renderStarRating($state['pivot']['rating']))
                                            ->columns(5)
                                            ->grow(false)
                                            ->extraAttributes([
                                                'class' => 'fi-infolist-star-rating'
                                            ])
                                ]),
                                $this->tabAction(
                                    'editSkillDetails',
                                    [
                                        ComponentsTabs::make('Label')
                                            ->tabs(SkillsSchema::make()),
                                    ],
                                    'xl'
                                ),
                            ])
                    ])
            ]);
    }

    private function tabAction(string $id, array | Closure | null $form, string $modalWidth = 'md') : ViewEntry
    {
        return ViewEntry::make($id)
            ->extraAttributes([
                'class' => 'absolute top-3 right-3',
                'action' => $id
            ], true)
            ->view('filament.infolists.tabs.edit-action')
            ->alignRight()
            ->alignEnd()
            ->action(
                Action::make($id)
                    ->hiddenLabel()
                    ->icon('heroicon-o-pencil-square')
                    ->fillForm(function(SkillResourceService $service) : array {
                        $data = $this->getRecord()->toArray();
                        $skills = $service->formatDataForDisplay($this->getRecord());

                        return [...$data, ...$skills];
                    })
                    ->slideOver()
                    ->modalWidth($modalWidth)
                    ->form($form)
                    ->afterFormValidated(function(array $data, Jobseeker $jobseeker) {
                        $data = app(SkillResourceService::class)->formatFormSkillsData($data);

                        if (app(JobseekerResourceService::class)->editProfile($this->getRecord(), $data)) {
                            Notification::make()
                                ->success()
                                ->title("Edit details successfully")
                                ->send();
                        } else {
                            Notification::make()
                                ->success()
                                ->title("Edit details successfully")
                                ->send();
                        }
                    }),
            );
    }

    /**
     * @param int $rating
     */
    private function renderStarRating($rating) : array
    {
        $skills = [];

        foreach(range(1, 5) as $number) {
            $skills[] = IconEntry::make('pivot.rating')
                ->icon('heroicon-m-star')
                ->hiddenLabel()
                ->grow()
                ->color(fn (string $state): string => $number <= $state ? 'primary' : 'gray')
            ;
        }

        return $skills;
    }
}
