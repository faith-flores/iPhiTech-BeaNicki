<?php

namespace App\Filament\Services;

use App\Models\Account;
use App\Models\Profile;
use App\Models\Services\ModelService;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class AccountResourceService extends ModelService
{

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string|Account
     */
    public function getClassName()
    {
        return Account::class;
    }

    /**
     * @param array $data
     *
     * @return bool|Model
     */
    public function add($data) : bool|Profile
    {
        $profile = Profile::make($data);

        if ($account_id = Arr::get($data, 'account_id')) {
            if($account = Account::query()->find($account_id)) {
                $profile->account()->associate($account);
            }
        }

        if ($user_id = Arr::get($data, 'user_id')) {
            if($user = User::find($user_id)){
                $profile->user()->associate($user);
            }
        }

        if ($profile->save()) {
            return $profile;
        }

        return false;
    }

    /**
     * @param array $data
     *
     * @return bool|Model
     */
    public function update($data, Account $account)
    {
        $account->fill($data);

        $account = $this->fillRelations($account, $data);

        if ($this->save($account)) {
            return $account;
        }
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

        $account = $this->update($data, $account);

        return $account;
    }

    public function fillRelations($account, $data)
    {
        if (Arr::exists($data, 'address')) {
            if ($address = Arr::pull($data, 'address')) {
                $account->address()->updateOrCreate([], $address);
            }
        }

        return $account;
    }
}
