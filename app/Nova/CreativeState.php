<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class CreativeState extends CustomizedResource
{

    public static $model = 'App\CreativeState';

    public static $search = ['name'];

}

