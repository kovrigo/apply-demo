<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Comment extends CustomizedResource
{

    public static $model = 'App\Comment';

    public static $search = [];

}

