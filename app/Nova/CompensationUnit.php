<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class CompensationUnit extends CustomizedResource
{

    public static $model = 'App\CompensationUnit';

    public static $search = ['name'];

}

