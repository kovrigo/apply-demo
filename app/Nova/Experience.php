<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Experience extends CustomizedResource
{

    public static $model = 'App\Experience';

    public static $search = ['name'];

}

