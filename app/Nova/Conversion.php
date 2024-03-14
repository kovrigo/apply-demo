<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Conversion extends CustomizedResource
{

    public static $model = 'App\Conversion';

    public static $search = ['id'];

}

