<?php

namespace App\Filament\Services;

use App\Models\Picklist;
use App\Models\PicklistItem;
use App\Models\Services\ModelService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class PicklistResourceService extends ModelService
{

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string|Picklist
     */
    public function getClassName()
    {
        return Picklist::class;
    }

    public function findByIdentifier($identifier)
    {
        return $this->findOneBy(['identifier' => $identifier]);
    }

    /**
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLabelList() {

        $query = $this->query();
        $query->select('id', 'label');
        return $query->get();
    }

    /**
     * @param string $identifier
     *
     * @return mixed
     */
    public function getCachedSelectableList(string $identifier)
    {
        return Cache::rememberForever('picklist::' . $identifier, function() use ($identifier) {
            if ($picklist = $this->getSelectableList($identifier)) {
                return $picklist;
            } else {
                throw new \Exception(sprintf('Pick List %s does not exist', $identifier));
            }
        });
    }

    /**
     * @param $identifier
     *
     * @return null|object
     */
    public function getSelectableList($identifier)
    {
        if (!$picklist = $this->findPicklist($identifier)) {
            return null;
        }

        /**
         * @var Builder $query
         */
        $query = $picklist->items();
        $query->select('id', 'identifier', 'label');
        $query->orderBy('sequence', 'ASC');
        $query->orderBy('label', 'ASC');

        $query->where('status', true);

        $picklist = $picklist->toArray();
        $items = $query->get();
        $picklist['items'] = $items->mapWithKeys(function($item){
            return [$item->identifier => $item->toArray()];
        });

        return $picklist;
    }

    /**
     * Find a pick list
     *
     * @param $identifier
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findPicklist($identifier)
    {
        return $this->query()->where('identifier', $identifier)->first();
    }

    /**
     * @param string $identifier
     */
    public function clearCachedSelectableList(string $identifier)
    {
        Cache::forget('picklist::' . $identifier);
    }

    /**
     * Get's the specific property of a pick list item or an array of values based on the given key
     *
     * @param string $pick_list_identifier The Pick List the item belongs to
     * @param string $item_identifier The item identifier
     * @param string|array $key A single key to extract off the item, or an array of keys to extract more than one property off the item
     * @param null $default The default value to return if none is foudn
     * @return array|mixed|null
     */
    public function getPickListItem($pick_list_identifier, $item_identifier, $key = 'label', $default = null)
    {
        $list = $this->getCachedSelectableList($pick_list_identifier);
        if ($list && isset($list['items'][$item_identifier])) {
            if (is_array($key)) {
                return Arr::only($list['items'][$item_identifier], $key);
            } else {
                return Arr::get($list['items'][$item_identifier], $key);
            }
        }
        return $default;
    }

    public function readItem($item)
    {
        $pickListItem = PicklistItem::find($item->getKey());

        if ($pickListItem) {
            return $pickListItem;
        }

        return false;
    }
}
