<?php

declare(strict_types=1);

namespace App\Filament\Resources\SkillResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SkillItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'skill_items';

    protected static ?string $recordTitleAttribute = 'Skill';

    protected static ?string $title = 'Skills';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('identifier')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('sequence')
                    ->maxLength(100),
                Forms\Components\TextArea::make('description')
                    ->maxLength(65535),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('identifier'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('sequence'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make('New Skill')
                    ->label('New Skill'),
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DissociateBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
