<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class NewOrderState extends CustomizedResource
{

    public static $model = 'App\NewOrderState';

    public static $search = ['name'];

}

