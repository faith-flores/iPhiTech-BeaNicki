<?php

namespace App\Listeners;

use App\Models\Account;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
    public function handle(Registered $event): void
    {
        $user = $event->user;
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
}
