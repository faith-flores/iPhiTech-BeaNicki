<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Filament\Resources\JobResource\RelationManagers;
use App\Filament\Resources\JobResource\RelationManagers\AccountRelationManager;
use App\Models\Account;
use App\Models\Job;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Jobs';

    /**
     * @var string|Closure|null
     */
    private static ?string $relationshipName = "account";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->maxLength(16777215),
                Forms\Components\TextInput::make('working_hours')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('schedule_id')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('type_of_work_id')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('skill_level_id')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('total_hire_count')
                    ->numeric()
                    ->default(1)
                ,
                Forms\Components\TextInput::make('salary')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('interview_availability'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('working_hours'),
                Tables\Columns\TextColumn::make('schedule_id'),
                Tables\Columns\TextColumn::make('type_of_work_id'),
                Tables\Columns\TextColumn::make('skill_level_id'),
                Tables\Columns\TextColumn::make('total_hire_count'),
                Tables\Columns\TextColumn::make('salary'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('interview_availability')
                    ->date(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AccountRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'view' => Pages\ViewJob::route('/{record}'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
            ]);
    }

    /**
     * @param   Job $job
     * @param array $data
     *
     * @return Model
     */
    public static function fillRelations(Job $job, $data) : Model
    {
        if ($account_id = Arr::get($data, 'account')) {
            if($account = Account::query()->find($account_id)){
                $job->account()->associate($account);
            }
        }

        return $job;
    }
}
