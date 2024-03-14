<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\AdvertisingPlatformInterest;
use App\Ad;

class AdvertisingPlatformInterestPolicy extends CustomizedPolicy
{

    public function addAd(User $user, AdvertisingPlatformInterest $model)
    {
        return $this->policy->can('addAd', $model);
    }

}

