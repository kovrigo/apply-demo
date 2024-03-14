<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ProjectRate extends CustomizedResource
{

    public static $model = 'App\ProjectRate';

    public static $search = [];

}

