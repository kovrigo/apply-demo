<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class User extends CustomizedResource
{

    public static $model = 'App\User';

    public static $search = ['first_name', 'last_name', 'email'];

}

