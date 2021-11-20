<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->check()) {
<<<<<<< HEAD
            if ($guard == 'cliants') {
                return "cliants";
            }

            return "users";
=======
            return redirect('/');
>>>>>>> 3e98f0127e3653c6328c7b663312ee536f6c9346
        }

        return $next($request);
    }
}
