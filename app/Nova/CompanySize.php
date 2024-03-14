<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class CompanySize extends CustomizedResource
{

    public static $model = 'App\CompanySize';

    public static $search = ['name'];

}

