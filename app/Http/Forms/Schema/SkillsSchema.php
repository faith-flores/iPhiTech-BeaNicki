<?php

declare(strict_types=1);

namespace App\Http\Forms\Schema;

use App\Filament\Services\SkillResourceService;
use App\Forms\Components\StarRating;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;

class SkillsSchema
{
    public static function make()
    {
        $skills = app(SkillResourceService::class)->query()->select('skills.*')->with('skill_items')->get();

        $schema = [];

        foreach ($skills as $key => $skill) {
            $options = $skill->skill_items()->pluck('label', 'id')->toArray();

            $schema[$key] = Tabs\Tab::make($skill->label)->schema([
                Repeater::make($skill->getRepeaterFieldKey())
                    ->label('Skills')
                    ->schema([
                        Select::make('skill_item_id')
                            ->label('Select Skill')
                            ->options($options)
                            ->in(array_column($options, 'id'))
                            ->required(),
                        StarRating::make('rating')
                            ->in([1, 2, 3, 4, 5])
                            ->required(),
                    ])
                    ->addActionLabel('Add Skill')
                    ->columns(2)
                    ->defaultItems(2)
                    ->collapsible()
                    ->itemLabel(fn (array $state): string => $options[$state['skill_item_id']] ?? ''),
            ]);
        }

        return $schema;
    }
}
