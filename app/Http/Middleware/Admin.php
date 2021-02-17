<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
       $type= getRoleType(Auth::user()->role);

        if (Auth::check() && $type =="user") {
            return redirect()->route('user');
        }

        elseif (Auth::check() && $type =="admin") {
            return $next($request);
        }
        else {
            return redirect()->route('login');
        }

    }
}
