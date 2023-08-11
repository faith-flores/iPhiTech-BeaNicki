<?php

namespace App\Models;

use App\Models\Contracts\HasAddress;
use App\Models\Traits\SkillRelations;
use App\Models\Traits\WithAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Jobseeker extends Model implements HasAddress
{
    use HasFactory;
    use WithAddress;
    use SkillRelations;

    /**
     * @var array The fillable values
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'nickname',
        'email',
        'phone_number',
        'date_of_birth',
        'job_title',
        'skills_summary',
        'experience',
        'website_url',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:sP',
        'updated_at' => 'datetime:Y-m-d\TH:i:sP',
        'deleted_at' => 'datetime:Y-m-d\TH:i:sP',
        'is_profile_completed' => 'bool',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }

    /**
     * @return MorphToMany
     */
    public function skills() : MorphToMany
    {
        return $this->morphToMany(SkillItem::class, 'skill_itemable')->withPivot('rating');
    }

    /**
     * @return BelongsTo
     */
    public function employment_status() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'employment_status_id');
    }

    /**
     * @return BelongsTo
     */
    public function hours_to_work() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'hours_to_work_id');
    }

    /**
     * @return BelongsTo
     */
    public function desired_salary() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'desired_salary_id');
    }

    /**
     * @return bool
     */
    public function isProfileCompleted() : bool
    {
        return $this->is_profile_completed === true;
    }

    public function setProfileCompleted()
    {
        $this->is_profile_completed = true;

        return $this->save();
    }
}
