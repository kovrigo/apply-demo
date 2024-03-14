<?php

namespace App;

use App\Settings\Model;

class Department extends Model 
{
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Relations\Department;
	use \App\Restrictions\Department;

}

