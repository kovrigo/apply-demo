<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class CreativeLog extends CustomizedResource
{

    public static $model = 'App\CreativeLog';

    public static $search = ['id'];

}

