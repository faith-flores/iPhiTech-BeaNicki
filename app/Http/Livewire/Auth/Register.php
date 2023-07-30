<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Register extends Component
{
    /** @var string */
    public $name = '';

    /** @var string */
    public $email = '';

    /** @var string */
    public $password = '';

    /** @var string */
    public $passwordConfirmation = '';

    /** @var string */
    public $role = '';

    public function register()
    {
        $this->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'same:passwordConfirmation'],
        ]);

        $user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
        ]);

        $role = Role::findByName($this->role);

        if ($role) {
            $user->syncRoles($role);
        } else {
            $this->addError('register', 'There wrong process.');
            return;
        }

        event(new Registered($user));

        Auth::login($user, true);

        return redirect()->intended(route('filament.pages.dashboard'));
    }

    public function registerAsEmployer()
    {
        $this->role = User::USER_ROLE_EMPLOYER;
    }

    public function registerAsJobseeker()
    {
        $this->role = User::USER_ROLE_JOBSEEKER;
    }

    public function render()
    {
        return view('livewire.auth.register')->extends('layouts.auth');
    }
}
