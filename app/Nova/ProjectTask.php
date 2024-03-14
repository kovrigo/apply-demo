<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use App\Workflows\WorkflowableResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ProjectTask extends CustomizedResource
{
	use \App\Settings\Traits\HasSortableRows;

    public static $model = 'App\ProjectTask';

    public static $search = ['name'];

}

