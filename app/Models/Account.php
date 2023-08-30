<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasAddress;
use App\Models\Traits\WithAddress;
use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

class Account extends Model implements HasAddress
{
    use HasFactory;
    use WithAddress;

    const ACCOUNT_TYPE_PERSONAL = 0;
    const ACCOUNT_TYPE_BUSINESS = 1;

    protected $table = 'profiles_accounts';

    /**
     * @var array The fillable values
     */
    protected $fillable = [
        'account_type',
        'company_name',
        'email',
        'company_phone',
        'web_url',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return AccountFactory
     */
    protected static function newFactory()
    {
        return new AccountFactory();
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function master_profile() : HasOne
    {
        return $this->hasOne(Profile::class, 'user_id', 'owner_user_id');
    }

    public function owner_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function billings() : HasMany
    {
        return $this->hasMany(Billing::class, 'account_id');
    }

    public function default_billing() : HasOne
    {
        return $this->hasOne(Billing::class, 'account_id')
            ->where('is_default', true);
    }

    public function profiles() : HasMany
    {
        return $this->hasMany(Profile::class);
    }

    public function fillRelations(self $account, $data)
    {
        if ($account->getKey() === auth()->user()->account->getKey()) {
            if (Arr::exists($data, 'address')) {
                if ($address = Arr::get($data, 'address')) {
                    $account->address->updateOrCreate([], $address);
                }
            }
        }

        return $account;
    }
}
