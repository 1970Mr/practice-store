<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSameSiteNone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sameSiteRoutes = [
            'transactions/checkout',
            // for when refresh
            'transactions/callback/*',
        ];
        foreach ($sameSiteRoutes as $route) {
            if ($request->is($route)) {
                config(['session.same_site' => null]);
                break;
            }
        }

        return $next($request);
    }
}
