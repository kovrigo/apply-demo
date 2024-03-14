<?php

namespace App;

use App\Settings\Model;

class CreativeState extends Model
{
	use \App\Relations\CreativeState;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Translatable\CreativeState;
	use \App\Restrictions\CreativeState;

}

