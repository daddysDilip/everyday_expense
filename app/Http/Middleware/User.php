<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class User
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
        if (Auth::check() && $type == "admin") {
            return redirect()->route('admin');
        }

        elseif (Auth::check() && $type =="user") {
            return $next($request);
        }
        else {
            return redirect()->route('login');
        }

    }
}
