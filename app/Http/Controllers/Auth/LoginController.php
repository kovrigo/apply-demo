<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        $user = User::where("id", $this->guard()->user()->id)->first();
        if (is_null($user->api_token)) {
            $user->api_token = User::generateApiToken();
            $user->save(); 
        }
        return $this->authenticated($request, $this->guard()->user())
                ?: response()->json($user);
    }
    
    public function logout(Request $request)
    {
        info('logout');
        $user = User::where("id", $this->guard()->user()->id)->first();
        $user->api_token = User::generateApiToken();
        $user->save();
        return $this->loggedOut($request) ?: response()->json();
    }

}
