<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class JobState extends CustomizedResource
{

    public static $model = 'App\JobState';

    public static $search = ['name'];

}

