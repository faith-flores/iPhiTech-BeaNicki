<?php

declare(strict_types=1);

namespace App\Http\Forms\Schema;

use App\Http\Forms\Schema\Contracts\HasFormSchema;
use App\Http\Forms\Schema\Types\DesiredSalary;
use App\Http\Forms\Schema\Types\EmploymentStatus;
use App\Http\Forms\Schema\Types\HoursToWork;
use App\Http\Forms\Schema\Types\Jobseeker\Gender;
use App\Http\Forms\Schema\Types\Jobseeker\HighestEducationAttainment;
use App\Http\Forms\Schema\Types\Phone;
use App\Http\Forms\Schema\Types\Website;
use App\Models\Jobseeker;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;

class JobseekerProfileWizardSchema implements HasFormSchema
{
    public static function make()
    {
        return [
            static::wizardStepPersonal(),
            static::wizardStepProfile(),
            static::wizardStepSkills(),
        ];
    }

    private static function wizardStepPersonal()
    {
        return Step::make('Personal')
            ->icon('heroicon-o-identification')
            ->schema([
                Shout::make('required')
                    ->content('You need to complete your profile details.')
                    ->type('warning'),
                Section::make('Profile Details')
                    ->description('Please complete your profile information below.')
                    ->schema([
                        ProfileSchema::make()
                            ->model(Jobseeker::class),
                        AddressSchema::make()
                            ->relationship('address')
                            ->model(Jobseeker::class),
                    ]),
            ]);
    }

    private static function wizardStepProfile()
    {
        return Step::make('Jobseeker Profile')
            ->icon('heroicon-o-identification')
            ->schema([
                Shout::make('required')
                    ->content('You need to complete your jobseeker details.')
                    ->type('warning'),
                TextInput::make('job_title')->required(),
                Textarea::make('skills_summary')->required(),
                HighestEducationAttainment::make(),
                Gender::make(),
                DesiredSalary::make(),
                TextInput::make('experience')->required(),
                EmploymentStatus::make(),
                HoursToWork::make(),
                Phone::make(),
                Website::make(),
            ])->model(Jobseeker::class);
    }

    private static function wizardStepSkills()
    {
        return Step::make('Skills Rating')
            ->schema([
                Shout::make('required')
                    ->content('You can skip this step.')
                    ->type('info'),
                Group::make([
                    Tabs::make('Label')
                        ->tabs(SkillsSchema::make()),
                ]),
            ]);
    }
}
