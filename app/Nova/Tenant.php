<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use App\Nova\Actions\Deploy;

class Tenant extends CustomizedResource
{

    public static $model = 'App\Tenant';

    public static $search = ['name'];

    public function actions(Request $request)
    {
    	$actions = parent::actions($request);
    	$deployAction = (new Deploy)->canSee(function ($request) {
    		return config('app.env') != 'production';
        });
        array_push($actions, $deployAction);
        return $actions;
    }

}

