<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\Uuidable;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use Uuidable;
    use HasFactory;
    use HasRoles;
    use SoftDeletes;

    const USER_ROLE_JOBSEEKER = "Jobseeker";
    const USER_ROLE_EMPLOYER = "Employer";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function(User $model){
            if ($model->isSuperAdmin()) {
                throw new \Exception('You cannot delete a Super User');
            }
            if ($model->getKey() === 1) {
                throw new \Exception('You cannot delete the master user account');
            }
        });
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return UserFactory
     */
    protected static function newFactory()
    {
        return new UserFactory();
    }

    /**
     * @return HasOne
     */
    public function account() : HasOne
    {
        return $this->hasOne(Account::class, 'owner_user_id');
    }

    /**
     * @return bool
     */
    public function isAdmin() : bool {
        return false;
        // return ($this->roles->contains('slug', '==', config('permission.admin.role', 'admin')) || $this->isSuperAdmin());
    }

    /**
     * @return bool
     */
    public function isActive() {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function isSuperAdmin() : bool {
        return !!$this->is_super_admin;
    }
}
