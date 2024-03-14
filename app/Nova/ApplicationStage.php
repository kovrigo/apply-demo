<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ApplicationStage extends CustomizedResource
{

    public static $model = 'App\ApplicationStage';

    public static $search = ['name'];

}

