<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillItem extends Model
{
    use HasFactory;

    protected $attributes = [
        'sequence' => 0,
        'status' => true,
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
        'status',
    ];

    public function skill() : BelongsTo
    {
        return $this->belongsTo(Skill::class)->withoutGlobalScopes();
    }

    public function scopeOfSkillIdentifier(Builder $query, $slug) : Builder
    {
        return $query->whereHas('skill', function ($query) use ($slug) {
            $query->ofSlug($slug);
        });
    }
}
