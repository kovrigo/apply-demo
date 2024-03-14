<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class Project extends WorkflowableResource
{
	use \App\Settings\Traits\HasSortableRows;

    public static $model = 'App\Project';

    public static $search = ['name'];

}

