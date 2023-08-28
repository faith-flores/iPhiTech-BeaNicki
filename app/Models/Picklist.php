<?php

namespace App\Models;

use App\Models\PicklistItem;
use App\Models\Traits\Sluggable;
use Database\Factories\PicklistFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO: Add Model Policy for Picklist
 */
class Picklist extends Model
{
    const SLUGGABLE_COLUMN  = 'identifier';
    const SLUGGABLE_SOURCE  = 'label';

    use Sluggable;
    use HasFactory;

    protected $table = 'picklists';

    protected $attributes = [
        'is_system' => false
    ];

    protected $fillable = [
        'label',
        'description',
        'is_tag'
    ];

    protected $visible = [
        'id',
        'label',
        'description',
        'identifier',
        'is_tag',
        'default_item',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_tag' => 'boolean'
    ];

    public function items()
    {
        return $this->hasMany(PicklistItem::class, 'picklist_id')->withoutGlobalScopes();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory()
    {
        return new PicklistFactory();
    }
}
