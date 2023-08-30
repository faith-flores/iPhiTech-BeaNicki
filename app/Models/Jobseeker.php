<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasAddress;
use App\Models\Traits\SkillRelations;
use App\Models\Traits\WithAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Jobseeker extends Model implements HasAddress, HasMedia
{
    use HasFactory;
    use WithAddress;
    use SkillRelations;
    use InteractsWithMedia;

    protected $appends = ['display_name'];

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
        'iq_score',
        'english_score',
        'disc_dominance_score',
        'disc_dominance_url',
        'disc_influence_score',
        'disc_influence_url',
        'disc_compliance_score',
        'disc_compliance_url',
        'disc_steadiness_score',
        'disc_steadiness_url',
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
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function skills() : MorphToMany
    {
        return $this->morphToMany(SkillItem::class, 'skill_itemable')->withPivot('rating');
    }

    public function employment_status() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'employment_status_id');
    }

    public function gender() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'gender_id');
    }

    public function hours_to_work() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'hours_to_work_id');
    }

    public function desired_salary() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'desired_salary_id');
    }

    public function education_attainment() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'education_attainment_id');
    }

    protected function getDisplayNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

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
