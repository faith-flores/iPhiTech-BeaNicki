<?php

declare(strict_types=1);

namespace App\Filament\Services;

use App\Models\Jobseeker;
use App\Models\Services\ModelService;
use App\Models\Skill;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

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
     * Format the data from tab, repeater schema.
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
     * Find a pick list.
     *
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findSkill($identifier)
    {
        return $this->query()->where('identifier', $identifier)->first();
    }

    public function formatDataForDisplay(Jobseeker $jobseeker) : null|array
    {
        $data = [];

        foreach ($jobseeker->skills as $key => $skillItem) {
            $data[$skillItem->skill->getRepeaterFieldKey()][] = [
                'skill_item_id' => $skillItem->getKey(),
                'rating' => $skillItem->pivot->rating,
            ];
        }

        return $data;
    }

    public function getCachedSelectableList(string $identifier)
    {
        return Cache::rememberForever('skill::' . $identifier, function () use ($identifier) {
            if ($skill = $this->getSelectableList($identifier)) {
                return $skill;
            } else {
                throw new \Exception(sprintf('Pick List %s does not exist', $identifier));
            }
        });
    }

    /**
     * @return null|object
     */
    public function getSelectableList($identifier)
    {
        if (! $skill = $this->findSkill($identifier)) {
            return null;
        }

        /**
         * @var Builder $query
         */
        $query = $skill->skill_items();
        $query->select('id', 'identifier', 'label');
        $query->orderBy('sequence', 'ASC');
        $query->orderBy('label', 'ASC');

        $query->where('status', true);

        $skill = $skill->toArray();
        $items = $query->get();
        $skill['items'] = $items->mapWithKeys(function ($item) {
            return [$item->identifier => $item->toArray()];
        });

        return $skill;
    }

    public function clearCachedSelectableList(string $identifier)
    {
        Cache::forget('skill::' . $identifier);
    }

    /**
     * Get's the specific property of a pick list item or an array of values based on the given key.
     *
     * @param string $item_identifier The item identifier
     * @param string|array $key A single key to extract off the item, or an array of keys to extract more than one property off the item
     * @param null $default The default value to return if none is foudn
     * @return array|mixed|null
     */
    public function getSkillItem($skill_identifier, $item_identifier, $key = 'label', $default = null)
    {
        $list = $this->getCachedSelectableList($skill_identifier);

        if ($list && isset($list['items'][$item_identifier])) {
            if (is_array($key)) {
                return Arr::only($list['items'][$item_identifier], $key);
            } else {
                return Arr::get($list['items'][$item_identifier], $key);
            }
        }

        return $default;
    }
}
