<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Models\Account;
use App\Models\Job;
use App\Models\PicklistItem;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Jobs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Card::make()
                            ->columnSpan(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label("Job Post Title")
                                    ->required()
                                    ->maxLength(255),
                                Grid::make(2)
                                    ->schema([
                                    TextInput::make('salary')
                                        ->numeric()
                                        ->required(),
                                    DatePicker::make('start_date'),
                                    TextInput::make('total_hire_count')
                                        ->label("Total Vacancies")
                                        ->numeric()
                                        ->default(1),
                                    static::getTypeOfWorkInput(),
                                ]),
                                RichEditor::make('description')
                                    ->label("Job Overview")
                                    ->required(),
                        ]),
                        Card::make()
                            ->columnSpan(1)
                            ->schema([
                                Fieldset::make()
                                    ->label("Schedule Details")
                                    ->columns(1)
                                    ->schema([
                                        TextInput::make('working_hours')
                                            ->required()
                                            ->maxLength(255),
                                        static::getScheduleInput(),
                                        TextInput::make('hours_per_week')
                                            ->label("Hours Per Week")
                                            ->numeric()
                                            ->required(),

                                    ]),
                                    static::getSkillLevelInput(),
                                    // TODO: Add Skills selection
                            ]),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('schedule.label')->searchable(),
                TextColumn::make('skill_level.label')->searchable(),
                TextColumn::make('type_of_work.label')->searchable(),
                TextColumn::make('created_at')
                    ->dateTime(),
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
            ->account()
            ->withoutGlobalScopes([
            ]);
    }

    public static function getTypeOfWorkInput() : Select
    {
        return Select::make('type_of_work_id')
            ->required()
            ->label("Employee Classication Type")
            ->relationship('type_of_work', 'label', (function (Builder $query) {
                $query->ofPicklistIdentifier('type-of-work');
            }));
    }

    public static function getScheduleInput() : Select
    {
        return Select::make('schedule_id')
            ->required()
            ->relationship('schedule', 'label', (function (Builder $query) {
                $query->ofPicklistIdentifier('schedule');
            }));
    }

    public static function getSkillLevelInput() : Select
    {
        return Select::make('skill_level_id')
            ->required()
            ->label("Preferred Experience Level")
            ->relationship('skill_level', 'label', (function (Builder $query) {
                $query->ofPicklistIdentifier('skill-level');
            }));
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

        if ($skill_level_id = Arr::get($data, 'skill_level_id')) {
            if($picklistItem = PicklistItem::query()->find($skill_level_id)){
                $job->skill_level()->associate($picklistItem);
            }
        }

        if ($schedule = Arr::get($data, 'schedule_id')) {
            if($picklistItem = PicklistItem::query()->find($schedule)){
                $job->schedule()->associate($picklistItem);
            }
        }

        if ($type_of_work = Arr::get($data, 'type_of_work_id')) {
            if($picklistItem = PicklistItem::query()->find($type_of_work)){
                $job->type_of_work()->associate($picklistItem);
            }
        }

        return $job;
    }
}
