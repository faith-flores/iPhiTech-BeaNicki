<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PicklistResource\Pages;
use App\Filament\Resources\PicklistResource\RelationManagers;
use App\Filament\Resources\PicklistResource\RelationManagers\PicklistsRelationManager;
use App\Models\Picklist;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PicklistResource extends Resource
{
    protected static ?string $model = Picklist::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    protected static ?string $navigationGroup = 'Admin Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::getLabelInput(),
                static::getDefaultItemInput()->columnSpan('full'),
                Grid::make(2)
                    ->schema([
                        static::getIsTagInput(),
                        static::getIsSystemInput(),
                    ]),
                static::getDescriptionInput(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->limit(50),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
                Tables\Columns\TextColumn::make('default_item'),
                Tables\Columns\IconColumn::make('is_tag')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_system')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PicklistsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPicklists::route('/'),
            'create' => Pages\CreatePicklist::route('/create'),
            'edit' => Pages\EditPicklist::route('/{record}/edit'),
        ];
    }

    public static function getLabelInput()
    {

        return Forms\Components\TextInput::make('label')
            ->maxLength(255)
            ->afterStateUpdated(function (Closure $get, Closure $set, ?string $state) {
                if (! $get('is_slug_changed_manually') && filled($state)) {
                    $set('identifier', Str::slug($state, "_"));
                }
            })
            ->reactive()
            ->required()
            ->columnSpan('full');
    }

    public static function getDefaultItemInput()
    {
        return Forms\Components\Select::make('default_item');
    }

    public static function getIsTagInput()
    {
        return Forms\Components\Toggle::make('is_tag');
    }

    public static function getIsSystemInput()
    {
        return Forms\Components\Toggle::make('is_system');
    }

    public static function getDescriptionInput()
    {
        return Forms\Components\Textarea::make('description')
            ->maxLength(1000)
            ->columnSpan('full');
    }
}
