<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Compensation extends CustomizedResource
{

    public static $model = 'App\Compensation';

    public static $search = ['id'];

}

