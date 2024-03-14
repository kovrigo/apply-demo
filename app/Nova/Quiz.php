<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Quiz extends CustomizedResource
{

    public static $model = 'App\Quiz';

    public static $search = ['name'];

}

