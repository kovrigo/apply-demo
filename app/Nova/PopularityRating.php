<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class PopularityRating extends CustomizedResource
{

    public static $model = 'App\PopularityRating';

    public static $search = ['name'];

}

