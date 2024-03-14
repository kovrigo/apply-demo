<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Industry extends CustomizedResource
{

    public static $model = 'App\Industry';

    public static $search = ['name'];

}

