<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = Account::query()->get();

        foreach ($accounts as $account) {
            $profile = Profile::factory()->make();
            $profile->account()->associate($account);
            $profile->user()->associate($account->owner_user);
            $profile->save();
        }
    }
}
