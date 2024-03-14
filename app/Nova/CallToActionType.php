<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class CallToActionType extends CustomizedResource
{

    public static $model = 'App\CallToActionType';

    public static $search = ['name'];

}

