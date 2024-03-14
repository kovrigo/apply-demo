<?php

namespace App\Nova;

use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Application extends WorkflowableResource
{

    public static $model = 'App\Application';

    public static $search = ['id'];

}

