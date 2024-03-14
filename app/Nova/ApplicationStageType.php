<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ApplicationStageType extends CustomizedResource
{

    public static $model = 'App\ApplicationStageType';

    public static $search = ['name'];

}

