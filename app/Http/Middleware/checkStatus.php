<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class checkStatus
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
        if (!Auth::user()->is_active) {
            $error = 'You are curently suspended, please contact us.';
            if (request()->wantsJson()) {
                return response(['error' => $error]);
            }
            Auth::logout();
            return redirect('/login')->with('error', $error);

        }
        return $next($request);
    }
}