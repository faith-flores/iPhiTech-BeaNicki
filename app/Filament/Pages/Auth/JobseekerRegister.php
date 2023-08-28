<?php

namespace App\Filament\Pages\Auth;

use App\Events\UserRegistered;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class JobseekerRegister extends Register
{

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        $user = $this->getUserModel()::create($data);

        $role = Role::findByName('jobseeker');
        $user->syncRoles($role);

        event(new UserRegistered($user, $role));

        Filament::auth()->login($user);
        Auth::login($user, true);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }
}
