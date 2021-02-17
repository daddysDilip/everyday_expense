<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

   // protected $redirectTo = RouteServiceProvider::HOME;

   protected $redirectTo;

   public function redirectTo()
   {
    $type= getRoleType(Auth::user()->role);

    switch($type){
           case 'admin':
           $this->redirectTo = '/admin';
           return $this->redirectTo;
               break;
           case 'user':
               $this->redirectTo = '/user';
               return $this->redirectTo;
               break;

           default:
               $this->redirectTo = '/login';
               return $this->redirectTo;
       }
       // return $next($request);
   }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = 'username';

    }


    public function logout(Request $request)
    {
        $this->guard()->logout();

       $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/login');
    }

    public function username()
    {
        return 'username';
    }

}
