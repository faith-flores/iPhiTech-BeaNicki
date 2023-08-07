<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Model::unguard();

        /**
         * Register model dynamic relations
         */
        User::resolveRelationUsing('profile', function ($userModel) {
            return $userModel->hasOne(Profile::class, 'user_id');
        });
        User::resolveRelationUsing('account', function ($userModel) {
            return $userModel->hasOne(Account::class, 'owner_user_id');
        });
    }
}
