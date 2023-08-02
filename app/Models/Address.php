<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    use HasFactory;

    protected $table = "addresses";

    protected $attributes = [
        'country' => 'PH'
    ];

    protected $fillable = [
        'address_line_1',
        'address_line_2',
        'street',
        'city',
        'province',
        'zip_code',
        'country',
        'address_type'
    ];

    /**
     * Get the parent addressable model.
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
