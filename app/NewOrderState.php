<?php

namespace App;

use App\Settings\Model;

class NewOrderState extends Model
{
	use \App\Relations\NewOrderState;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Translatable\NewOrderState;
	use \App\Restrictions\NewOrderState;

}

