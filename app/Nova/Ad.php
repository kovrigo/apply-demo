<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Ad extends CustomizedResource
{

    public static $model = 'App\Ad';

    public static $search = ['id'];

}

