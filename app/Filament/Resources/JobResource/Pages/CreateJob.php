<?php

declare(strict_types=1);

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $job = static::getModel()::make($data);
        $data['account'] = auth()->user()->account->getKey();

        $job = static::getResource()::fillRelations($job, $data);

        $job->save();

        return $job;
    }
}
