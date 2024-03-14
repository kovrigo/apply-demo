<?php

namespace App\Http\Controllers;

use Laravel\Nova\Http\Controllers\LoginController as BaseLoginController;
use Illuminate\Http\Request;
use App\User;

class LoginController extends BaseLoginController
{
    
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (is_null($user->api_token)) {
            $user->api_token = User::generateApiToken();
            $user->saveWithoutEvents();
        }
        $redirect = apply()->settings->home;
        return redirect($redirect);        
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        $user->api_token = null;
        $user->saveWithoutEvents();
    }

}
