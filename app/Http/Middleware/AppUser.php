<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class AppUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = 'appuser')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('iniciar.sesion.user');
        }
        return $next($request);
    }
}
