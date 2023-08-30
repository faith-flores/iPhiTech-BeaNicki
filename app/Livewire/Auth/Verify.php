<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Verify extends Component
{
    public function resend()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            if (Auth::guard('jobseeker')->check()) {
                return redirect()->intended(route('filament.jobseekers.pages.dashboard'));
            } else {
                return redirect()->intended(route('filament.app.pages.dashboard'));
            }
        }

        Auth::user()->sendEmailVerificationNotification();

        $this->emit('resent');

        session()->flash('resent');
    }

    public function render()
    {
        return view('livewire.auth.verify')->extends('layouts.auth');
    }
}
