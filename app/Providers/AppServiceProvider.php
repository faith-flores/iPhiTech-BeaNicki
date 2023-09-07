<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Account;
use App\Models\Jobseeker;
use App\Models\Profile;
use App\Models\User;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use MailchimpMarketing\ApiClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ApiClient::class, function ($app) {
            $client = new ApiClient();

            $client->setConfig([
                'apiKey' => env('MAILCHIMP_API_KEY'),
                'server' => env('MAILCHIMP_SERVER_PREFIX'),
            ]);

            return $client;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Model::unguard();

        /**
         * Register model dynamic relations.
         */
        User::resolveRelationUsing('profile', function ($userModel) {
            return $userModel->hasOne(Profile::class, 'user_id');
        });

        User::resolveRelationUsing('account', function ($userModel) {
            return $userModel->hasOne(Account::class, 'owner_user_id');
        });

        User::resolveRelationUsing('jobseeker', function ($userModel) {
            return $userModel->hasOne(Jobseeker::class, 'user_id');
        });

        Auth::provider('guarded', function ($app, array $config) {
            return new GuardedUserProvider($app['hash'], $config['model'], $config['guard']);
        });

        FilamentColor::register([
            'primary' => Color::hex('#865DFF'),
            'secondary' => Color::hex('#E384FF'),
        ]);

        FilamentView::registerRenderHook(
            'panels::topbar.start',
            fn (): string => '<div class="fi-nav-container mx-auto w-full px-4 md:px-6 lg:px-8 max-w-7xl flex items-center gap-x-4">',
        );

        FilamentView::registerRenderHook(
            'panels::topbar.end',
            fn (): string => '</div>',
        );
    }
}
