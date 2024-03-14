<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class PlaceGroupAddress extends CustomizedResource
{

    public static $model = 'App\PlaceGroupAddress';

    public static $search = ['address_line_1'];

}

