<?php

namespace App;

use App\Settings\Model;

class Order extends Model 
{
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Relations\Order;
	use \App\Restrictions\Order;

}

