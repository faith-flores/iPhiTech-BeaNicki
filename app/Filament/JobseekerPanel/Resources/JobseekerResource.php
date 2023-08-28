<?php

namespace App\Filament\JobseekerPanel\Resources;

use App\Filament\JobseekerPanel\Resources\JobseekerResource\Pages;
use App\Models\Jobseeker;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class JobseekerResource extends Resource
{
    protected static ?string $model = Jobseeker::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'account';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobseekers::route('/'),
            'edit' => Pages\ViewJobseekerProfile::route('/{record}/edit'),
            'setup-profile' => Pages\JobseekerProfileWizard::route('/{record}/setup-profile'),
        ];
    }
}
