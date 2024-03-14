<?php

namespace App;

use App\Settings\Model;

class JobPageTemplate extends Model 
{
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Relations\JobPageTemplate;
	use \App\Restrictions\JobPageTemplate;

}

