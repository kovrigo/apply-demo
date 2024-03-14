<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ApplicationLog extends CustomizedResource
{

    public static $model = 'App\ApplicationLog';

    public static $search = ['id'];

}

