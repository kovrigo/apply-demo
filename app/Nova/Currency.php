<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Currency extends CustomizedResource
{

    public static $model = 'App\Currency';

    public static $search = ['name'];

}

