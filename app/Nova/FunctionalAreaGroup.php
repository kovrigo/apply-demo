<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class FunctionalAreaGroup extends CustomizedResource
{

    public static $model = 'App\FunctionalAreaGroup';

    public static $search = ['name'];

}

