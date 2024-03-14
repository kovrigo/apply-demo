<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class SkillLevel extends CustomizedResource
{

    public static $model = 'App\SkillLevel';

    public static $search = ['name'];

}

