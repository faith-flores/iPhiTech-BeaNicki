<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class JobsSeeder extends Seeder
{
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

        /**
         * TODO: Fix this seeder haha
         */
        $skill_levels = ["Beginner", "Intermediate", "Advanced", "Expert"];
        $type_of_works = ["Remote", "On-site", "Hybrid"];
        $hours_to_works = ["1-10 hours", "11-20 hours", "21-30 hours", "31-40 hours", "40+ hours"];
        $schedule = ["Day Shift", "Night Shift", "Flexible", "Weekdays Only", "Weekends Only"];

        foreach ($profiles as $key => $profile) {
            if (! empty($data[$key])) {

                foreach ($data[$key] as $key => $list) {
                    $job = Job::factory()
                            ->make($list)
                        ;

                    $relations = [
                        'skill_level' => picklist_item('skill-level', Str::slug($skill_levels[rand(0, 3)]), 'id'),
                        'schedule' => picklist_item('schedule', Str::slug($schedule[rand(0, 4)]), 'id'),
                        'hours_to_work' => picklist_item('hours-to-work', Str::slug($hours_to_works[rand(0, 4)]), 'id'),
                        'type_of_work' => picklist_item('type-of-work', Str::slug($type_of_works[rand(0, 2)]), 'id'),
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
