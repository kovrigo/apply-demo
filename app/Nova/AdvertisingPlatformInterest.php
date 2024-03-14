<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class AdvertisingPlatformInterest extends CustomizedResource
{

    public static $model = 'App\AdvertisingPlatformInterest';

    public static $search = ['advertising_platform_uuid'];

}

