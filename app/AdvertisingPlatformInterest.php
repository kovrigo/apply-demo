<?php

namespace App;

use App\Settings\Model;

class AdvertisingPlatformInterest extends Model
{
	use \App\Relations\AdvertisingPlatformInterest;
	use \App\Restrictions\AdvertisingPlatformInterest;

    public static $resourceTitleAttribute = "default_title";

    public function getDefaultTitleAttribute()
    {
        return $this->interest->name . " (" . 
            $this->advertisingPlatform->name . ")";
    }

}

