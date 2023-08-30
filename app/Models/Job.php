<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\Sluggable;
use Database\Factories\JobFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;

class Job extends Model
{
    use HasFactory;
    use Sluggable;

    const SLUGGABLE_COLUMN = 'identifier';
    const SLUGGABLE_SOURCE = 'title';

    const JOB_STATUS_DRAFT = 0;
    const JOB_STATUS_OPEN = 1;
    const JOB_STATUS_CLOSED = 2;

    protected $fillable = [
        'title',
        'description',
        'hours_to_work_id',
        'salary',
        'start_date',
        'interview_availability',
        'status',
        'skill_level_id',
        'type_of_work_id',
        'total_hire_count',
        'schedule_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return JobFactory
     */
    protected static function newFactory()
    {
        return new JobFactory();
    }

    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function profile() : BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function skills() : MorphToMany
    {
        return $this->morphToMany(SkillItem::class, 'skill_itemable');
    }

    public function schedule() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'schedule_id');
    }

    public function skill_level() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'skill_level_id');
    }

    public function type_of_work() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'type_of_work_id');
    }

    public function hours_to_work() : BelongsTo
    {
        return $this->belongsTo(PicklistItem::class, 'hours_to_work_id');
    }

    public function scopeAccount(Builder $builder) : Builder
    {
        // Allow admin to see all Jobs
        if (auth()->user()->isSuperAdmin()) {
            return $builder;
        }

        return $builder->whereBelongsTo(auth()->user()->account);
    }

    public function scopeActive(Builder $builder) : Builder
    {
        // Allow admin to see all Jobs
        return $builder->where('status', '=', self::JOB_STATUS_OPEN);
    }

    public function fillRelations($job, $data)
    {
        if ($picklist_item_id = Arr::get($data, 'skill_level')) {
            if ($pickListItem = PickListItem::query()->find($picklist_item_id)) {
                $job->skill_level()->associate($pickListItem);
            }
        }
        if ($picklist_item_id = Arr::get($data, 'hours_to_work')) {
            if ($pickListItem = PickListItem::query()->find($picklist_item_id)) {
                $job->hours_to_work()->associate($pickListItem);
            }
        }
        if ($picklist_item_id = Arr::get($data, 'schedule')) {
            if ($pickListItem = PickListItem::query()->find($picklist_item_id)) {
                $job->schedule()->associate($pickListItem);
            }
        }
        if ($picklist_item_id = Arr::get($data, 'type_of_work')) {
            if ($pickListItem = PickListItem::query()->find($picklist_item_id)) {
                $job->type_of_work()->associate($pickListItem);
            }
        }

        return $job;
    }
}
