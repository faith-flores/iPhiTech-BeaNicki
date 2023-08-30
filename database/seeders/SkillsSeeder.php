<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_path = base_path('database/data/skills.json');

        if (file_exists($file_path)) {
            $data = File::get($file_path);
            $data = json_decode($data, true);

            $this->seedSkills($data);
        }
    }

    private function seedSkills(array $data)
    {
        foreach ($data as $data_skill) {
            $skill_items = Arr::pull($data_skill, 'items');

            $skill = Skill::query()->make($data_skill);
            $skill->save();

            SkillItem::reguard();
            $skill->skill_items()->createMany($skill_items);
        }
    }
}
