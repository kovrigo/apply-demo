<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ProjectLog extends CustomizedResource
{

    public static $model = 'App\ProjectLog';

    public static $search = ['id'];

}

