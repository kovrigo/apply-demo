<?php

namespace App;

use App\Settings\Model;

class PlaceGroupAddress extends Model
{
    use \App\Relations\PlaceGroupAddress;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\PlaceGroupAddress;

    protected $casts = [
        'business_hours' => 'array',
    ];

}

