<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use App\Events\UserRegistered;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getTermsAgreement(),
                $this->getPromotionalAgreement(),
            ])
            ->statePath('data');
    }

    protected function getTermsAgreement(): Component
    {
        return Checkbox::make('terms')
            ->label('I agree to the Terms of Service and Privacy Policy')
            ->required();
    }

    protected function getPromotionalAgreement(): Component
    {
        return Checkbox::make('promotional')
            ->label('I agree to receive promotional emails and updates.')
            ->required();
    }
}
