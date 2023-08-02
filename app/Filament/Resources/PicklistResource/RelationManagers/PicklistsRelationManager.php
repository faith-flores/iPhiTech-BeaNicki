<?php

namespace App\Filament\Resources\PicklistResource\RelationManagers;

use App\Filament\Resources\PicklistResource;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PicklistsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'label';

    protected static string $resource = PicklistResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                static::$resource::getLabelInput(),
                static::getSequenceInput()->columnSpan(1),
                static::getIsDefaultItemInput()->columnSpan(1)->inline(false),
                Grid::make(2)
                    ->schema([
                        static::$resource::getIsTagInput(),
                        static::$resource::getIsSystemInput(),
                    ]),
                static::$resource::getDescriptionInput(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('sequence'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\IconColumn::make('is_tag')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_default_item')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_system')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getSequenceInput()
    {
        return Forms\Components\TextInput::make('sequence')->numeric()->default(0);
    }

    public static function getIsDefaultItemInput()
    {
        return Forms\Components\Toggle::make('is_default_item');
    }
}
