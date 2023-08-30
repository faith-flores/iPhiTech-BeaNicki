<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\Uuidable;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, HasName, FilamentUser
{
    use HasApiTokens, Notifiable;
    use Uuidable;
    use HasFactory;
    use HasRoles;
    use SoftDeletes;

    const USER_ROLE_JOBSEEKER = 'jobseeker';
    const USER_ROLE_EMPLOYER = 'employer';

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
        static::deleting(function (self $model) {
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

    public function getFilamentName(): string
    {
        return $this->name ?? '';
    }

    public function account() : HasOne
    {
        return $this->hasOne(Account::class, 'owner_user_id');
    }

    public function isAdmin() : bool
    {
        return false;
        // return ($this->roles->contains('slug', '==', config('permission.admin.role', 'admin')) || $this->isSuperAdmin());
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    public function isSuperAdmin() : bool
    {
        return (bool) $this->is_super_admin;
    }

    public function scopeHasRole(Builder $query, string $role)
    {
        return $query->whereHas('roles', fn ($query) => $query->where('name', $role));
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (Auth::guard('jobseeker')->check()) {
            return $panel->getAuthGuard() === 'jobseeker';
        }

        if (Auth::guard('web')->check()) {
            return $panel->getAuthGuard() === 'web';
        }

        return false;
    }
}
