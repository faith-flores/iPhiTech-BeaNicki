<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillItem extends Model
{
    use HasFactory;

    protected $attributes = [
        'sequence' => 0,
        'status' => true
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'label',
        'identifier',
        'description',
        'default_item',
        'sequence',
        'status'
    ];

    /**
     * @return BelongsTo
     */
    public function skills() : BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
