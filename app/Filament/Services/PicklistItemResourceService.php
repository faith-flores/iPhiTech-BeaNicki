<?php

declare(strict_types=1);

namespace App\Filament\Services;

use App\Models\PicklistItem;
use App\Models\Services\ModelService;

class PicklistItemResourceService extends ModelService
{
    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string|PicklistItem
     */
    public function getClassName()
    {
        return PicklistItem::class;
    }
}
