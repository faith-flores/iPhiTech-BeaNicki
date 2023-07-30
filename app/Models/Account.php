<?php

namespace App\Models;

use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    use HasFactory;

    const ACCOUNT_TYPE_PERSONAL = 0;
    const ACCOUNT_TYPE_BUSINESS = 1;

    protected $table = "profiles_accounts";

    /**
     * @var array The fillable values
     */
    protected $fillable = [
        'account_type',
        'company_name',
        'email',
        'company_phone',
        'web_url',
        'is_active',
        'is_multi_user',
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

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id')->withTrashed();
    }
}
/**
 * TODO: Profile Wizard
 *
 */
