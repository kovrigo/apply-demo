<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class QuizCompletion extends CustomizedResource
{

    public static $model = 'App\QuizCompletion';

    public static $search = ['id'];

}

