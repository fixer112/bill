<?php

namespace App\Http\Middleware;

use Closure;

class webRouteEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!env("ENABLE_WEB_ROUTE")) {
            return abort(503);
        }
        return $next($request);
    }
}