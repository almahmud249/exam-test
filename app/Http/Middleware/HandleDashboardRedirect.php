<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class HandleDashboardRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route()->named('dashboard')) {
            $route_name = $request->user()->account_type . ".dashboard";

            if (Route::has($route_name)) {
                return redirect()->route($route_name);
            }
        }

        return $next($request);
    }
}
