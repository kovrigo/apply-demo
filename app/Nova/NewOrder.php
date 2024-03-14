<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class NewOrder extends WorkflowableResource
{

    public static $model = 'App\NewOrder';

    public static $search = [];

}

