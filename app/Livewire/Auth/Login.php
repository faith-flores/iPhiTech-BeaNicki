<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    /** @var string */
    public $email = '';

    /** @var string */
    public $password = '';

    /** @var bool */
    public $remember = false;

    /** @var string */
    public $loginRole = '';

    protected $rules = [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ];

    public function authenticate()
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));

            return;
        }

        if (Auth::guard('jobseeker')->check()) {
            return redirect()->intended(route('filament.jobseekers.pages.dashboard'));
        } else {
            return redirect()->intended(route('filament.app.pages.dashboard'));
        }
    }

    public function setLoginRole($role)
    {
        $this->loginRole = $role;
    }

    public function render()
    {
        return view('livewire.auth.login')->extends('layouts.auth');
    }
}
