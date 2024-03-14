<?php

namespace App;

use App\Settings\Model;

class Artist extends Model 
{
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Relations\Artist;
	use \App\Restrictions\Artist;
}
