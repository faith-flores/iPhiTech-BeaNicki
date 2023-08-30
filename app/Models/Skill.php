<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Skill extends Model
{
    use HasFactory;

    const REPEATER_PREFIX = 'skill_items_';

    /**
     * @var string[]
     */
    protected $fillable = [
        'label',
        'identifier',
        'description',
        'default_item',
        'is_tag',
    ];

    /**
     * @var string[]
     */
    protected $visible = [
        'id',
        'label',
        'identifier',
        'description',
        'default_item',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_tag' => 'bool',
        'created_at' => 'datetime:Y-m-d\TH:i:sP',
        'updated_at' => 'datetime:Y-m-d\TH:i:sP',
    ];

    public function skill_items() : HasMany
    {
        return $this->hasMany(SkillItem::class, 'skill_id')->withoutGlobalScopes();
    }

    public function getRepeaterFieldKey()
    {
        return self::REPEATER_PREFIX . Str::slug($this->label);
    }
}
