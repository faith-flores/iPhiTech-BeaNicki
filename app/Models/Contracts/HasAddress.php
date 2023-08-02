<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface HasAddress
{

    /**
     * Register a address relationship
     *
     * @return BelongsTo
     */
    public function address();
}
