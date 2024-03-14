<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class FunctionalArea extends CustomizedResource
{

    public static $model = 'App\FunctionalArea';

    public static $search = ['name'];

    public static $optionsGroup = 'functional_area_group_title';

}

