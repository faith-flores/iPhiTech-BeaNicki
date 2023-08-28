<?php

namespace App\Providers\Filament;

use App\Filament\JobseekerPanel\Resources\JobseekerResource;
use App\Filament\JobseekerPanel\Widgets\StatsOverview;
use App\Http\Middleware\Jobseeker\EnsureProfileIsCompleted;
use App\Livewire\OurBrandsInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class JobseekerPanelProvider extends PanelProvider
{
    protected array $userMenuItems = [];

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('jobseekers')
            ->path('jobseekers')
            ->login()
            ->viteTheme('resources/css/filament/jobseekers/theme.css')
            ->discoverResources(in: app_path('Filament/JobseekerPanel/Resources'), for: 'App\\Filament\\JobseekerPanel\\Resources')
            ->discoverPages(in: app_path('Filament/JobseekerPanel/Pages'), for: 'App\\Filament\\JobseekerPanel\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/JobseekerPanel/Widgets'), for: 'App\\Filament\\JobseekerPanel\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                StatsOverview::class,
                OurBrandsInfoWidget::class
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                                ->label('Edit Profile')
                                ->url(fn() => JobseekerResource::getUrl('edit', [auth()->user()->jobseeker]))
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->authGuard('jobseeker')
            ->authMiddleware([
                Authenticate::class,
                EnsureEmailIsVerified::class,
                EnsureProfileIsCompleted::class
            ]);
    }
}
