<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\Sluggable;
use Database\Factories\PicklistItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * TODO: Add Model Policy for PicklistItem.
 */
class PicklistItem extends Model
{
    const SLUGGABLE_COLUMN = 'identifier';
    const SLUGGABLE_SOURCE = 'label';

    use Sluggable;
    use HasFactory;

    protected $table = 'picklist_items';

    protected $attributes = [
        'is_system' => false,
        'sequence' => 0,
        'status' => true,
    ];

    protected $fillable = [
        'label',
        'description',
        'sequence',
        'status',
        'meta',
    ];

    protected $visible = [
        'id',
        'label',
        'description',
        'identifier',
        'sequence',
        'status',
        'meta',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_system' => 'boolean',
        'meta' => 'array',
    ];

    public function picklist()
    {
        return $this->belongsTo(Picklist::class)->withoutGlobalScopes();
    }

    /**
     * @param int|Picklist $picklist
     */
    public function setPicklistAttribute($picklist)
    {
        if (! $picklist instanceof Picklist && ! empty($picklist)) {
            $picklist = Picklist::query()->find($picklist);
        }
        if ($picklist) {
            $this->picklist()->associate($picklist);
        }
    }

    /**
     * Get models with given slug.
     */
    private function getRelatedSlugs($slug)
    {
        if (method_exists($this, 'trashed')) {
            $query = static::withTrashed();
        } else {
            $query = static::query();
        }
        $query->select($this->getQualifiedSluggableColumn(), $this->getQualifiedSluggableSourceColumn())
            ->where($this->getQualifiedSluggableColumn(), 'like', $slug . '%')
            ->where('picklist_id', $this->picklist_id);

        //ensure not the same record
        if ($key = $this->getKey()) {
            $query->where($this->getQualifiedKeyName(), '!=', $key);
        }

        return $query->get();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new PicklistItemFactory();
    }

    public function scopeOfPicklistIdentifier(Builder $query, $slug) : Builder
    {
        return $query->whereHas('picklist', function ($query) use ($slug) {
            $query->ofSlug($slug);
        });
    }

    /**
     * @param null $default
     *
     * @return array|\ArrayAccess|mixed|null
     */
    public function getMetaValue($key, $default = null)
    {
        if ($this->meta) {
            return Arr::get($this->meta, $key, $default);
        }

        return $default;
    }

    public function getIsDefaultAttribute()
    {
        if ($this->default_item) {
            return $this->default_item === $this->id;
        } else {
            return null;
        }
    }
}
