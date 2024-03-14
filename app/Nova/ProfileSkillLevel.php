<?php

namespace App\Nova;

use App\Settings\CustomizedResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class ProfileSkillLevel extends CustomizedResource
{
	use \App\Settings\Traits\HasSortableRows;

    public static $model = 'App\ProfileSkillLevel';

    public static $search = ['id'];

}

