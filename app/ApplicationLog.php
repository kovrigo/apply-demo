<?php

namespace App;

use App\Settings\Model;

class ApplicationLog extends Model
{
   	use \App\Relations\ApplicationLog;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\ApplicationLog;
    
}

