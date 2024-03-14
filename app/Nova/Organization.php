<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Organization extends CustomizedResource
{

    public static $model = 'App\Organization';

    public static $search = ['name'];

}

