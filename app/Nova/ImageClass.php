<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ImageClass extends CustomizedResource
{

    public static $model = 'App\ImageClass';

    public static $search = ['name'];

}

