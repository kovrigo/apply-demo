<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class AdvertisingPlatformTransaction extends CustomizedResource
{

    public static $model = 'App\AdvertisingPlatformTransaction';

    public static $search = ['id'];

}

