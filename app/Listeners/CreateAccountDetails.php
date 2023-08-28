<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Filament\Services\JobseekerResourceService;
use App\Models\Account;
use App\Models\Jobseeker;
use App\Models\User;

class CreateAccountDetails
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        if ($event->role === User::USER_ROLE_EMPLOYER) {
            $this->createClientAccount($user);
        } else if ($event->role === User::USER_ROLE_JOBSEEKER) {
            $this->createJobseekerProfile($user);
        }
    }

    private function createClientAccount($user)
    {
        $account = Account::query()->where('owner_user_id', $user->getKey())->first();

        if (empty($account)) {
            $account = Account::query()->make([
                'email'      => $user->email,
                'is_active'  => true,
            ]);

            $account->user()->associate($user);

            $account->save();
        }
    }

    private function createJobseekerProfile($user)
    {
        $jobseeker = Jobseeker::query()->where('user_id', $user->getKey())->first();

        if (empty($jobseeker)) {
            $data = [
                'user_id' => $user->getKey(),
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'nickname' => $user->name,
            ];

            app(JobseekerResourceService::class)->add($data);
        }
    }
}
