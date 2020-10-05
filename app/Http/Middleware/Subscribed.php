<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Subscribed
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
        if (Auth::user()->is_reseller && !Auth::user()->subscriptions->last()) {
            return redirect()->route('subscribe', ['user' => $request->user->id]);
        }
        return $next($request);
    }
}