<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Transaction extends CustomizedResource
{

    public static $model = 'App\Transaction';

    public static $search = ['id'];

}

