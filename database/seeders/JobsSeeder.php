<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Filament\Services\PicklistResourceService;
use App\Models\Job;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class JobsSeeder extends Seeder
{
    private PicklistResourceService $service;

    public function __construct(PicklistResourceService $service)
    {
        $this->service = $service;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_path = base_path('database/data/jobs.json');

        if (file_exists($file_path)) {
            $data = File::get($file_path);
            $data = json_decode($data, true);

            $this->seedJobs($data);
        }
    }

    public function seedJobs(array $data): void
    {
        $profiles = Profile::query()->get();

        if ($profiles->count() === 0) {
            $this->callSilent(ProfilesSeeder::class);

            $profiles = Profile::query()->get();
        }

        $skill_level = $this->service->getCachedSelectableList('skill-levels')['items']->random();
        $type_of_work = $this->service->getCachedSelectableList('type-of-work')['items']->random();
        $hours_to_work = $this->service->getCachedSelectableList('hours-to-work')['items']->random();
        $schedule = $this->service->getCachedSelectableList('schedule')['items']->random();

        foreach ($profiles as $key => $profile) {
            if (! empty($data[$key])) {
                foreach ($data[$key] as $key => $list) {
                    $job = Job::factory()
                            ->make($list);

                    $relations = [
                        'skill_level' => $skill_level['id'],
                        'schedule' => $schedule['id'],
                        'hours_to_work' => $hours_to_work['id'],
                        'type_of_work' => $type_of_work['id'],
                    ];

                    $job = $job->fillRelations($job, $relations);

                    $job->account()->associate($profile->account);
                    $job->profile()->associate($profile);

                    $job->save();
                }
            }
        }
    }
}
