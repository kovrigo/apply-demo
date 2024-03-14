<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class CreativeWorkflow extends CustomizedResource
{

    public static $model = 'App\CreativeWorkflow';

    public static $search = ['name'];

}

