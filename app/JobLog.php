<?php

namespace App;

use App\Settings\Model;

class JobLog extends Model
{
   	use \App\Relations\JobLog;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\JobLog;

}

