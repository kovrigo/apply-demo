<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Benefit extends CustomizedResource
{

    public static $model = 'App\Benefit';

    public static $search = ['name'];

}

