<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'label',
        'identifier',
        'description',
        'default_item',
        'is_tag'
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

    /**
     * @return HasMany
     */
    public function skill_items() : HasMany
    {
        return $this->hasMany(SkillItem::class);
    }
}
