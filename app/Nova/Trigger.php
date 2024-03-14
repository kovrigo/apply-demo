<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Trigger extends CustomizedResource
{

    public static $model = 'App\Trigger';

    public static $search = ['name'];

}

