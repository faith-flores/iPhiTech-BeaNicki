<?php

declare(strict_types=1);

namespace App\Filament\Services;

use App\Models\Account;
use App\Models\Profile;
use App\Models\Services\ModelService;
use App\Models\User;
use Illuminate\Support\Arr;

class ProfileResourceService extends ModelService
{
    /**
     * @var BillingService
     */
    private BillingResourceService $billingService;

    /**
     * Profile Model Service class.
     */
    public function __construct(BillingResourceService $billingService)
    {
        $this->billingService = $billingService;
    }

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string|Profile
     */
    public function getClassName()
    {
        return Profile::class;
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
            if ($account = Account::query()->find($account_id)) {
                $profile->account()->associate($account);
            }
        }

        if ($user_id = Arr::get($data, 'user_id')) {
            if ($user = User::find($user_id)) {
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
    public function update($data, Profile $profile)
    {
        $profile = $this->getModel($profile);

        $profile->fill($data);

        $profile = $this->fillRelations($profile, $data);

        if ($profile->save()) {
            return $profile;
        }
    }

    /**
     * Use sole purposely for the
     * Profile Wizard only.
     *
     * @param array $data
     */
    public function editProfile($data, User $user)
    {
        $account = $user->account;
        if (! $account) {
            return false;
        }

        $profile_data = $data['master_profile'];

        $profile_data['user_id'] = $user->getKey();
        $profile_data['account_id'] = $account->getKey();

        if ($account_type = Arr::pull($data, 'account_type')) {
            $account->account_type = $account_type;
        }

        if ($email = Arr::pull($profile_data, 'email')) {
            $account->email = $email;
        }

        $account->save();

        if ($account && $account->master_profile) {
            $profile = $this->update($profile_data, $account->master_profile);
        } else {
            $profile = $this->add($profile_data);
        }

        return $profile;
    }

    /**
     * Use sole purposely for the
     * Profile Wizard only.
     *
     * @param array $data
     *
     * @return mixed|bool|Model
     */
    public function editBillingDetails($data, Account $account)
    {
        $billing = $this->billingService->findByAccountId($account->getKey());

        if ($data['same_company_address'] == 'true') {
            $data['billing'] = [
                'company_name' => $account->company_name,
                'invoice_name' => $data['invoice_name'],
                'email' => $account->email,
                'address' => $account->address->toArray(),
            ];
        }

        $data['account_id'] = $account->getKey();

        $billing = $this->billingService->store($data, $account);

        if ($billing->profile->setProfileCompleted()) {
            return $billing;
        }

        return false;
    }

    private function fillRelations(Profile $profile, $data)
    {
        if ($billing = Arr::get($data, 'billing')) {
        }

        return $profile;
    }
}
