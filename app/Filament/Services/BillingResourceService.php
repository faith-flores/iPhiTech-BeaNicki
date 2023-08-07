<?php

namespace App\Filament\Services;

use App\Models\Account;
use App\Models\Billing;
use App\Models\Profile;
use App\Models\Services\ModelService;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class BillingResourceService extends ModelService
{

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string|Account
     */
    public function getClassName()
    {
        return Billing::class;
    }

    /**
     * @param $id
     *
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findByAccountId($id)
    {
        return $this->query()->where('account_id', $id)->first();
    }

    /**
     * @param array $data
     *
     * @return bool|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function add($data, Account $account)
    {
        $billing = self::make($data);

        if ($account_id = Arr::get($data, 'account_id')) {
            if($account = Account::query()->find($account_id)){
                $billing->account()->associate($account);
            }
        }

        $billing = $this->fillRelations($billing, $data);

        if ($this->save($billing)) {
            return $billing;
        } else {
            return false;
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int $id
     * @param array $data
     *
     * @return bool|Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|int|object|null
     */
    public function update(Billing $billing, $data)
    {
        $billing = $this->getModel($billing);

        $billing->fill($data);
        $billing = $this->fillRelations($billing, $data);

        if ($billing->save()) {
            return $billing;
        } else {
            return false;
        }
    }

    /**
     * Store the billing details for the given account
     *
     * @param array $data
     * @param Account       $account
     *
     * @return bool|Builder|Builder[]|Collection|Model|int|mixed|object
     * @throws Exception
     */
    public function store($data, Account $account)
    {
        $billing = $this->findByAccountId($account->getKey());

        if ($billing) {
            $billing = $this->update($billing, $data);
        } else {
            $billing = $this->add($data, $account);
        }

        if ($account->owner_user->profile && empty($account->owner_user->profile->billing_id)) {
            $account->owner_user->profile->billing()->associate($billing);
            $account->owner_user->profile->save();
        }

        return $billing;
    }

    /**
     * Use sole purposely for the
     * Profile Wizard only
     *
     * @param array $data
     */
    public function editBusinessDetails($data, Account $account)
    {
        $account = $this->getModel($account);

        $account = $this->update($account, $data);

        return $account;
    }

    /**
     * @param Billing $billing
     * @param array $data
     */
    public function fillRelations($billing, array $data) : Billing
    {
        if (Arr::exists($data, 'address')) {
            if ($address = Arr::pull($data, 'address')) {
                $billing->address()->updateOrCreate([], $address);
            }
        }

        $billing_count = $this->query()->where('account_id', $billing->account_id)->count();

        if($billing->exists &&  $billing_count == 1) { //if only 1 billing exists, automatically set this as default
            $billing->is_default = true;
        }
        else if(!$billing->exist &&  $billing_count == 0) { //if no billing exists, automatically set this as default
            $billing->is_default = true;
        }

        if($is_default = Arr::get($data, 'is_default')) { // only set one billing per account as default
            $this->query()
                ->where('id','!=',$billing->id)
                ->where('account_id', $billing->account_id)
                ->update(['is_default' => false]);
        }

        return $billing;
    }
}
