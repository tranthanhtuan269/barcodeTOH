<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;

class AuthenticateFrontEnd
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
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                if(Auth::check() && Auth::user()->status == 0){
                    Auth::logout();
                    return redirect('/');
                }
            } else {
                return redirect()->guest('/');
            }
        }
        
        return $next($request);
    }
}
