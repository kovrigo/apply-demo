<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ProjectRole extends CustomizedResource
{

    public static $model = 'App\ProjectRole';

    public static $search = ['name'];

}

