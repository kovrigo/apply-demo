<?php

namespace App\Nova;

use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Job extends WorkflowableResource
{

    public static $model = 'App\Job';

    public static $search = ['name'];

}

