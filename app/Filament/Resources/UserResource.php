<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->unique(
                        ignorable: fn (null|Model $record): null|Model => $record,
                    )
                    ->email()
                    ->unique()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->hiddenOn([Pages\EditUser::class, Pages\ViewUser::class])
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('role'),
                Forms\Components\Toggle::make('is_super_admin')
                    ->required(),
                Forms\Components\Toggle::make('active')
                    ->required(),
                Forms\Components\Toggle::make('logged_in')
                    ->required(),
                Forms\Components\Toggle::make('must_reset_password')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_super_admin')
                    ->boolean(),
                Tables\Columns\IconColumn::make('active')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\IconColumn::make('logged_in')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
            ])
            ->filters([
                Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->where('active', true)),
                Filter::make('employers')
                    ->label('Employers')
                    ->query(fn (Builder $query): Builder => $query->hasRole(User::USER_ROLE_EMPLOYER)),
                Filter::make('jobseekers')
                    ->label('Jobseekers')
                    ->query(fn (Builder $query): Builder => $query->hasRole(User::USER_ROLE_JOBSEEKER)),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'editProfile' => Pages\ProfileWizard::route('/client/{record}/edit-profile'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_super_admin', false)
            ->orderBy('id', 'DESC')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
