<?php

declare(strict_types=1);

if (! function_exists('picklist_item')) {
    /**
     * Get a pick list item.
     *
     * @param string $picklist_identifier The picklist identifier
     * @param string $picklist_item_identifier The picklist item identifier
     * @param null $key The key to fetch. Null fetches picklist data
     * @return array|mixed|null
     */
    function picklist_item(string $picklist_identifier, string $picklist_item_identifier, $key = null)
    {
        /** @var \App\Filament\Services\PicklistResourceService $service */
        $service = resolve(\App\Filament\Services\PicklistResourceService::class);

        return $service->getPickListItem($picklist_identifier, $picklist_item_identifier, $key);
    }
}

if (! function_exists('skill_item')) {
    /**
     * Get a pick list item.
     *
     * @param null $key The key to fetch. Null fetches picklist data
     * @return array|mixed|null
     */
    function skill_item(string $skill_identifier, string $skill_item_identifier, $key = null)
    {
        /** @var \App\Filament\Services\SkillResourceService $service */
        $service = resolve(\App\Filament\Services\SkillResourceService::class);

        return $service->getSkillItem($skill_identifier, $skill_item_identifier, $key);
    }
}
