<?php

namespace App\Http\Middleware\Jobseeker;

use App\Filament\JobseekerPanel\Resources\JobseekerResource;
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
        if ($request->route()->getName() !== JobseekerResource::getUrl('setup-profile', [$request->user()->jobseeker])) {
            $jobseeker = $request->user()->jobseeker;

            if ($jobseeker && ! $jobseeker->isProfileCompleted()) {
                return $request->expectsJson()
                        ? abort(403, 'You need to complete your profile!')
                        : redirect(JobseekerResource::getUrl('setup-profile', [$request->user()->jobseeker]));
            }
        }

        return $next($request);
    }
}
