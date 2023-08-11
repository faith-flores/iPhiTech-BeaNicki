<?php

namespace App\Filament\Services;

use App\Models\Picklist;
use App\Models\Services\ModelService;

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
}
