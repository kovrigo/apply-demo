<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class CpmGeoMultiplier extends CustomizedResource
{

    public static $model = 'App\CpmGeoMultiplier';

    public static $search = ['address_line_1'];

}

