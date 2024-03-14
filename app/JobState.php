<?php

namespace App;

use App\Settings\Model;

class JobState extends Model
{
    use \App\Relations\JobState;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Translatable\JobState;
	use \App\Restrictions\JobState;

}

