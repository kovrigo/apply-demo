<?php

namespace App;

use App\Settings\Model;

class Organization extends Model 
{
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Relations\Organization;
	use \App\Restrictions\Organization;

}

