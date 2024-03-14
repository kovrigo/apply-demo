<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ProjectWorkflow extends CustomizedResource
{

    public static $model = 'App\ProjectWorkflow';

    public static $search = ['name'];

}

