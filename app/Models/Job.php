<?php

namespace App\Models;

use Database\Factories\JobFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    const JOB_STATUS_DRAFT = 0;
    const JOB_STATUS_OPEN = 1;
    const JOB_STATUS_CLOSED = 2;

    protected $fillable = [
        'title',
        'description',
        'working_hours',
        'salary',
        'start_date',
        'interview_availability',
        'status',
        'skill_level_id',
        'type_of_work_id',
        'total_hire_count',
        'schedule_id'
    ];

    /**
     * @return BelongsTo
     */
    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return JobFactory
     */
    protected static function newFactory()
    {
        return new JobFactory();
    }
}
