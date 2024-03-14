<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class PlaceGroup extends CustomizedResource
{

    public static $model = 'App\PlaceGroup';

    public static $search = ['name'];

}

