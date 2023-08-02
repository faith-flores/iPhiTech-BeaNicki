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
        $account = $request->user()->account;

        if ($request->user()->hasRole(['Admin'])) return $next($request);

        if ($account && ! $account->master_profile->isProfileCompleted()) {
            return redirect(route('profiles.client.edit-profile', $request->user()));
        }

        return $next($request);
    }
}
