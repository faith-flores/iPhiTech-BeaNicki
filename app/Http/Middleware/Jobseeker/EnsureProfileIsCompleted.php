<?php

declare(strict_types=1);

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
        $exlude = ['filament.jobseekers.auth.logout', 'filament.jobseekers.resources.account.setup-profile'];

        if (! in_array($request->route()->getName(), $exlude)) {
            $jobseeker = $request->user()->jobseeker;

            if ($jobseeker && ! $jobseeker->isProfileCompleted()) {
                return $request->expectsJson()
                        ? abort(403, 'You need to complete your profile!')
                        : redirect(JobseekerResource::getUrl('setup-profile', [$request->user()->jobseeker->getKey()]));
            }
        }

        return $next($request);
    }
}
