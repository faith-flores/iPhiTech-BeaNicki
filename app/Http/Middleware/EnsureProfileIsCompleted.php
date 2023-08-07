<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->hasRole(['Admin'])) return $next($request);

        // Check if the route name is 'filament.app.resources.users.editProfile'
        if ($request->route()->getName() !== 'filament.app.resources.users.editProfile') {
            $account = $request->user()->account;

            if ($account && (! $account->master_profile || ! $account->master_profile->isProfileCompleted())) {
                return $request->expectsJson()
                        ? abort(403, 'You need to complete your profile!')
                        : redirect(route('filament.app.resources.users.editProfile', $request->user()));
            }
        }


        return $next($request);
    }
}
