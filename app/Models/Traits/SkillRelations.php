<?php

namespace App\Models\Traits;

use App\Models\SkillItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Trait HasSkillRelations
 *
 * @package App\Models\Traits
 */
trait SkillRelations
{
    /**
     * @param string $related
     * @return MorphToMany
     */
    public function morphToManySkillItems($related = SkillItem::class)
    {
        return $this->morphToMany($related, 'skill_itemable', 'skill_itemables', 'relatable_id', 'skill_item_id');
    }

    /**
     * @param string $related
     * @return BelongsTo
     */
    public function belongsToSkillItem($related = SkillItem::class, $foreignKey = null, $ownerKey = null, $relation = null)
    {
        return $this->belongsTo($related, $foreignKey, $ownerKey, $relation);
    }
}
