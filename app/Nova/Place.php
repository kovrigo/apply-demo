<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class Place extends CustomizedResource
{
	use \App\Settings\Traits\HasSortableRows;

    public static $model = 'App\Place';

    public static $search = ['address_line_1'];

}

