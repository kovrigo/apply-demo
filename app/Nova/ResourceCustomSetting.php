<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class ResourceCustomSetting extends CustomizedResource
{

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\ResourceCustomSetting';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'resource',
    ];

}
