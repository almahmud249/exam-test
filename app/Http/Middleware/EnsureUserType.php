<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $accountType)
    {
        if ($request->user() && $request->user()->account_type !== $accountType) {
            return redirect()->route('not.found'); // Redirect to a suitable route or show an error page
        }
        return $next($request);
    }
}
