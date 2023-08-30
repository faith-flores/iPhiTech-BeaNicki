<?php

declare(strict_types=1);

namespace App\Filament\JobseekerPanel\Resources;

use App\Filament\JobseekerPanel\Resources\JobResource\Pages;
use App\Models\Account;
use App\Models\Job;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected int $defaultPaginationPageOption = 12;

    protected static ?string $navigationLabel = 'Available Jobs';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(4)
                    ->schema([
                        Section::make()
                            ->schema([
                                IconEntry::make('type_of_work')
                                    ->hiddenLabel()
                                    ->icon('heroicon-o-briefcase'),
                                TextEntry::make('type_of_work.label')
                                    ->size('text-2xl')
                                    ->label('Type of Work'),
                            ])->columnSpan(1),

                            Section::make()
                            ->schema([
                                IconEntry::make('salary')
                                    ->hiddenLabel()
                                    ->icon('heroicon-o-banknotes'),
                                TextEntry::make('salary')
                                    ->size('text-2xl')
                                    ->label('Salary')
                                    ->money(),
                            ])->columnSpan(1),

                            Section::make()
                            ->schema([
                                IconEntry::make('hours_to_work')
                                    ->hiddenLabel()
                                    ->icon('heroicon-o-clock'),
                                TextEntry::make('hours_to_work.label')
                                    ->size('text-2xl')
                                    ->label('Hours Per Week'),
                            ])->columnSpan(1),

                            Section::make()
                            ->schema([
                                IconEntry::make('created_at')
                                    ->hiddenLabel()
                                    ->icon('heroicon-o-calendar'),
                                TextEntry::make('created_at')
                                    ->size('text-2xl')
                                    ->date()
                                    ->label('Date Posted'),
                            ])->columnSpan(1),
                    ]),
                Section::make('Job Overview')
                    ->schema([
                        TextEntry::make('description')
                            ->markdown(),
                    ]),
                Section::make('Notes')
                    ->schema([
                        TextEntry::make('description'),
                    ]),
                TextEntry::make('skills.label')
                    ->label('Skills and Expertise')
                    ->hidden(fn (Job $record) : bool => ! $record->skills()->count())
                    ->badge(),
            ]);
    }

    /**
     * TODO: Add dislike function,
     *       Add Favorite Action.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 1,
                'xl' => 1,
            ])
            ->columns([
                Stack::make([
                    /**
                     * TODO: Add job duration
                     *       Author Name / Company Name.
                     */
                    Split::make([
                        TextColumn::make('title')
                            ->size(TextColumnSize::Large)
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull()
                            ->grow(false)
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('type_of_work.label')
                            ->badge()
                            ->columnStart(3)
                            ->grow(false),
                    ]),
                    TextColumn::make('salary')->money(),
                    Split::make([
                        TextColumn::make('skill_level.label')
                            ->size(TextColumnSize::ExtraSmall)
                            ->suffix(' - ')
                            ->searchable()
                            ->grow(false),
                        TextColumn::make('created_at')
                            ->size(TextColumnSize::ExtraSmall)
                            ->since(),
                    ]),
                    Split::make([
                        TextColumn::make('description')
                            ->size(TextColumnSize::Medium)
                            ->extraAttributes([
                                'class' => 'mt-5',
                            ], true)
                            ->limit(500)
                            ->columnSpanFull(),
                    ]),
                    Split::make([
                        Stack::make([
                            TextColumn::make('skills.label')
                                ->badge()
                                ->searchable()
                                ->limitList(20),
                            TextColumn::make('account.company_name')
                                ->prefix('Posted by: ')
                                ->visible(fn (null|Job $record) : null|bool => $record?->account->account_type === Account::ACCOUNT_TYPE_BUSINESS),
                            TextColumn::make('profile.display_name')
                                ->prefix('Posted by: ')
                                ->visible(fn (null|Job $record) : null|bool => $record?->account->account_type === Account::ACCOUNT_TYPE_PERSONAL),
                        ])->space(3),
                    ]),
                ])->space(2),
            ])
            ->paginated([12, 24, 36]);
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
            'index' => Pages\ListAvailableJobs::route('/'),
            'view' => Pages\ViewJob::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->active()
            ->withoutGlobalScopes([
            ]);
    }

    public function getRouteKeyName()
    {
        return 'identifier';
    }
}
