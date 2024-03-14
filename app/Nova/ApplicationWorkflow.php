<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ApplicationWorkflow extends CustomizedResource
{

    public static $model = 'App\ApplicationWorkflow';

    public static $search = ['name'];

}

