<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Artist extends CustomizedResource
{

    public static $model = 'App\Artist';

    public static $search = ['name'];

}

