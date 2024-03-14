<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class WorkplaceType extends CustomizedResource
{

    public static $model = 'App\WorkplaceType';

    public static $search = ['name'];

}

