<?php

declare(strict_types=1);

namespace App\Filament\Resources\SkillResource\Pages;

use App\Filament\Resources\SkillResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSkills extends ListRecords
{
    protected static string $resource = SkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
