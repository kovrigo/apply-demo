<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Interest extends CustomizedResource
{

    public static $model = 'App\Interest';

    public static $search = ['name'];

}

