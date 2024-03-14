<?php

namespace App\Nova;

use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Creative extends WorkflowableResource
{

    public static $model = 'App\Creative';

    public static $search = ['title', 'description'];

}

