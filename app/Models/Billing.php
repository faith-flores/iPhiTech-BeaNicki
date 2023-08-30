<?php

namespace App\Models;

use App\Models\Contracts\HasAddress;
use App\Models\Traits\WithAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Billing extends Model implements HasAddress
{
    use HasFactory;
    use WithAddress;

    const BILLING_TYPE_PREPAID = 0;
    const BILLING_TYPE_ACCOUNT = 1;

    protected $table = "profiles_billing";

    /**
     * @var array The fillable values
     */
    protected $fillable = [
        'company_name',
        'invoice_name',
        'email',
        'phone',
        'tax_number',
        'billing_type',
        'is_default',
    ];

    /**
     * TODO: Need to finalize the date format
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime:Y-m-d\TH:i:sP',
        'updated_at' => 'datetime:Y-m-d\TH:i:sP',
        'deleted_at' => 'datetime:Y-m-d\TH:i:sP',
    ];

    /**
     * @return BelongsTo
     */
    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class,'account_id');
    }

    /**
     * @return HasOne
     */
    public function profile() : HasOne
    {
        return $this->hasOne(Profile::class, 'billing_id');
    }

    /**
     * @return bool
     */
    public function isPrepaidBillingType() : bool
    {
        return $this->billing_type === self::BILLING_TYPE_PREPAID;
    }

    /**
     * @return bool
     */
    public function isAccountBillingType() : bool
    {
        return $this->billing_type === self::BILLING_TYPE_ACCOUNT;
    }
}
