<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class NewOrderLog extends CustomizedResource
{

    public static $model = 'App\NewOrderLog';

    public static $search = ['id'];

}

