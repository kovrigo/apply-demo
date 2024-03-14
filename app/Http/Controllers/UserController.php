<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\UserProfile;
use Auth;
use Illuminate\Support\Facades\Artisan;

class UserController extends Controller
{

	// Set current user profile (role)
    public function setProfile(UserProfile $profile)
    {
        $user = Auth::user();
        $user->user_profile_id = $profile->id;
        $user->save();
        Artisan::call('settings:cache');
        return redirect('/');
    }

	// Home page redirects
    public function home()
    {
		if (Auth::user()) {
			if (Auth::user()->is_super_admin) {
				return redirect('/apply/resources/resource-settings');
			}
			return redirect(apply()->settings->home);
		}
		return redirect('/apply/resources/users');
    }

}
