<?php

namespace App\Http\Middleware\Jobseeker;

use Closure;
use Illuminate\Http\Request;
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
        // Check if the route name is 'filament.app.resources.users.editProfile'
        if ($request->route()->getName() !== 'filament.jobseekers.resources.accounts.edit-profile') {
            $jobseeker = $request->user()->jobseeker;

            if ($jobseeker && ! $jobseeker->isProfileCompleted()) {
                return $request->expectsJson()
                        ? abort(403, 'You need to complete your profile!')
                        : redirect(route('filament.jobseekers.resources.accounts.edit-profile', $request->user()->jobseeker));
            }
        }

        return $next($request);
    }
}
