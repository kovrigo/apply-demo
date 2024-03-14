<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class City extends CustomizedResource
{

    public static $model = 'App\City';

    public static $search = ['name'];

}

