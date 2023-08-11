<?php

namespace App\Filament\JobseekerPanel\Resources;

use App\Filament\JobseekerPanel\Resources\JobseekerResource\Pages;
use App\Filament\JobseekerPanel\Resources\JobseekerResource\Pages\JobseekerProfileWizard;
use App\Models\Jobseeker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JobseekerResource extends Resource
{
    protected static ?string $model = Jobseeker::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'accounts';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobseekers::route('/'),
            'edit' => Pages\EditJobseeker::route('/{record}/edit'),
            'edit-profile' => JobseekerProfileWizard::route('/{record}/edit-profile'),
        ];
    }
}
