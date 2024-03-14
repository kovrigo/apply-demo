<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class CustomFunctionalArea extends CustomizedResource
{
	use \App\Settings\Traits\HasSortableRows;

    public static $model = 'App\CustomFunctionalArea';

    public static $search = ['name'];

    public static $optionsGroup = 'custom_functional_area_group_title';

}

