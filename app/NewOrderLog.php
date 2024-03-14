<?php

namespace App;

use App\Settings\Model;

class NewOrderLog extends Model
{
	use \App\Relations\NewOrderLog;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\NewOrderLog;

}

