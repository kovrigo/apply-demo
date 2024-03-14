<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class JobPageTemplate extends CustomizedResource
{

    public static $model = 'App\JobPageTemplate';

    public static $search = ['name','code'];

}

