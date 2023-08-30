<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Address;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Trait WithAddress.
 *
 * @property string $address_line_1
 * @property string $address_line_2
 * @property string $street
 * @property string $city
 * @property string $province
 * @property string $country
 * @property string $zip_code
 */
trait WithAddress
{
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
