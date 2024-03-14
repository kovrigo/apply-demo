<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class LocaleController extends Controller
{

	// Set locale and save it in the session
    public function setLocale($locale)
    {
    	$locales = collect(config('translatable.locales'));
    	if ($locales->has($locale)) {
	        App::setLocale($locale);
	        session()->put('locale', $locale);
    	}
        return redirect('/');
    }

}
