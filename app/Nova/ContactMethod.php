<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ContactMethod extends CustomizedResource
{

    public static $model = 'App\ContactMethod';

    public static $search = ['name'];

}

