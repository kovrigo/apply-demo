<?php

namespace App;

use App\Settings\Model;
use App\Tenancy\Traits\RespectsTenancy;

class CreativeLog extends Model
{
    use \App\Relations\CreativeLog;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\CreativeLog;

}

