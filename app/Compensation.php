<?php

namespace App;

use App\Settings\Model;

class Compensation extends Model
{
    use \App\Relations\Compensation;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\Compensation;

    protected $table = 'compensations';

}

