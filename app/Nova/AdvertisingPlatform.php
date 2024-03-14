<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class AdvertisingPlatform extends CustomizedResource
{

    public static $model = 'App\AdvertisingPlatform';

    public static $search = ['name'];

}

