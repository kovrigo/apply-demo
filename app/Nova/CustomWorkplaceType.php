<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class CustomWorkplaceType extends CustomizedResource
{

    public static $model = 'App\CustomWorkplaceType';

    public static $search = ['id'];

}

