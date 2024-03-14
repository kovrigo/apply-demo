<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ProjectState extends CustomizedResource
{

    public static $model = 'App\ProjectState';

    public static $search = ['name'];

}

