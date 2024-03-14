<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Page extends CustomizedResource
{

    public static $model = 'App\Page';

    public static $search = ['name', 'page'];

}

