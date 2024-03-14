<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class NewOrderWorkflow extends CustomizedResource
{

    public static $model = 'App\NewOrderWorkflow';

    public static $search = ['name'];

}

