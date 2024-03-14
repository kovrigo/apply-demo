<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Order extends CustomizedResource
{

    public static $model = 'App\Order';

    public static $search = ['id'];

}

