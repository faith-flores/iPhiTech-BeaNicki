<?php

namespace App\Filament\Services;

use App\Models\Jobseeker;
use App\Models\Services\ModelService;
use App\Models\Skill;

class SkillResourceService extends ModelService
{

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string|Skill
     */
    public function getClassName()
    {
        return Skill::class;
    }

    /**
     * Format the data from tab, repeater schema
     *
     * @param array $data
     *
     * @return array
     */
    public function formatFormSkillsData(array $data) : array
    {
        $formData = collect($data);
        $skills = [];

        // Iterate through the collection and remove skill items while collecting them
        $formData->each(function ($value, $key) use (&$skills, $formData) {
            if (strpos($key, 'skill_items_') === 0) {
                $skills = [...$skills, ...$value];
                unset($formData[$key]);
            }
        });

        $formData['skills'] = $skills;

        return $formData->toArray();
    }

    /**
     * @param Jobseeker $jobseeker
     *
     * @return null|array
     */
    public function formatDataForDisplay(Jobseeker $jobseeker) : null|array
    {
        $data = [];

        foreach ($jobseeker->skills as $key => $skillItem) {
            $data[$skillItem->skill->getRepeaterFieldKey()][] = [
                'skill_item_id' => $skillItem->getKey(),
                'rating' => $skillItem->pivot->rating
            ];
        }

        return $data;
    }
}
