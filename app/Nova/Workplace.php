<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Workplace extends CustomizedResource
{

    public static $model = 'App\Workplace';

    public static $search = ['id'];

}

