<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class Article extends WorkflowableResource
{
	use HasSortableRows;

    public static $model = 'App\Article';

    public static $search = ['name'];

}

