<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Filament\Services\PicklistItemResourceService;
use App\Filament\Services\PicklistResourceService;
use App\Models\Picklist;
use App\Models\PicklistItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PicklistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_path = base_path('database/data/picklists.json');

        if (file_exists($file_path)) {
            $data = File::get($file_path);
            $data = json_decode($data, true);

            $this->seedPicklists($data);
        }
    }

    public function seedPicklists($data)
    {
        $picklistService = app(PicklistResourceService::class);
        $picklistItemService = app(PicklistItemResourceService::class);

        foreach ($data as $key => $list) {
            $picklist = new Picklist();
            $picklist->label = Arr::get($list, 'label');

            if ($identifier = Arr::get($list, 'identifier', null)) {
                $picklist->identifier = $identifier;
            }
            if ($is_tag = Arr::get($list, 'is_tag', false)) {
                $picklist->is_tag = $is_tag;
            }

            if ($exists = $picklistService->findOneBy(['identifier' => $picklist->identifier])) {
                $picklist = $exists;
                $picklistService->clearCachedSelectableList($picklist->identifier);
            } else {
                $picklistService->save($picklist);
            }

            if ($list['items']) {
                foreach ($list['items'] as $key => $item) {
                    if (! is_array($item)) {
                        $item = [
                            'label' => $item,
                            'identifier' => Str::slug($item),
                        ];
                    }

                    $identifier = Arr::get($item, 'identifier', null);

                    $pickListItem = $picklistItemService->findOneBy([
                        'identifier' => $identifier,
                        'picklist_id' => $picklist->getKey(),
                    ]);

                    $new = false;

                    if ($pickListItem) {
                        if ($meta = Arr::get($item, 'meta', null)) {
                            if (empty($pickListItem->meta)) {
                                $pickListItem->meta = $meta;
                            } else {
                                // This will not replace existing values but only append new items
                                $pickListItem->meta = array_merge($meta, $pickListItem->meta);
                            }
                        }
                    } else {
                        $new = true;

                        $pickListItem = new PicklistItem();
                        $pickListItem->picklist = $picklist;
                        $pickListItem->label = Arr::get($item, 'label');
                        if ($identifier = Arr::get($item, 'identifier', null)) {
                            $pickListItem->identifier = $identifier;
                        }
                        $pickListItem->sequence = Arr::get($item, 'sequence', 0);
                        $pickListItem->status = Arr::get($item, 'status', true);
                        $pickListItem->is_system = Arr::get($item, 'is_system', false);
                        $pickListItem->description = Arr::get($item, 'description', null);
                        if ($meta = Arr::get($item, 'meta', null)) {
                            $pickListItem->meta = $meta;
                        }
                    }

                    $picklistService->save($pickListItem);

                    if ($new && Arr::get($item, 'is_default')) {
                        $picklist->default_item = $pickListItem->getKey();
                        $pickListItem->save($picklist);
                    }
                }
            }
        }
    }
}
