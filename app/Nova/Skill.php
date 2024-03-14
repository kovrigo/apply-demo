<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Skill extends CustomizedResource
{

    public static $model = 'App\Skill';

    public static $search = ['name'];

}

