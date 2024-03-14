<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ProjectTaskLink extends CustomizedResource
{

    public static $model = 'App\ProjectTaskLink';

    public static $search = [];

}

