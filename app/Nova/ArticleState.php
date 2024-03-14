<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ArticleState extends CustomizedResource
{

    public static $model = 'App\ArticleState';

    public static $search = ['name'];

}

