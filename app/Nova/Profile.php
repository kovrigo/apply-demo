<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Profile extends CustomizedResource
{

    public static $model = 'App\Profile';

    public static $search = ['given_name', 'middle_name', 'family_name', 'nickname'];

}

