<?php

namespace App;

use App\Settings\Model;

class NewOrder extends Model 
{
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Workflows\Traits\Workflowable;
	use \App\Traits\RespectsOwnership;
	use \App\Relations\NewOrder;
	use \App\Restrictions\NewOrder;

}

