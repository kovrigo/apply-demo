<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ArticleLog extends CustomizedResource
{

    public static $model = 'App\ArticleLog';

    public static $search = ['id'];

}

