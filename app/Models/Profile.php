<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'profiles';

    /**
     * @var array The fillable values
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
    ];

    protected $appends = ['display_name'];

    protected $visible = [
        'id',
        'first_name',
        'last_name',
        'phone',
        'account_type',
        'country',
        'billing',
        'is_profile_completed',
        'created_at',
        'updated_at',
        'deleted_at',
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
    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function client_manager()
    {
        return $this->belongsTo(User::class, 'client_manager_id');
    }

    /**
     * @return BelongsTo
     */
    public function billing()
    {
        return $this->belongsTo(Billing::class, 'billing_id');
    }

    protected function getDisplayNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * @return bool
     */
    public function isProfileCompleted() : bool
    {
        return $this->is_profile_completed === true;
    }

    /**
     * Model Functions
     */

    public function setProfileCompleted()
    {
        $this->is_profile_completed = true;

        return $this->save();
    }


    public function editProfile(User $user, $data)
    {
        $profile = $this->query()->where('user_id', $user->getKey())->first();

        if ($user->account) {
            $user->account->fill($data);
            $user->account->fillRelations($user->account, $data);
        }

        if ($profile) {
            // update
            $profile->fill($data);
        } else {
            // add
            $profile = new Profile($data);
            $profile->account()->associate($user->account);
            $profile->user()->associate($user);
        }

        if ($profile->save()) {
            $user->account->save();

            return $profile;
        }

        throw new Exception("Unable to save profile data", 1);
    }
}
