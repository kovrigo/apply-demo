<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\AdvertisingPlatform;
use App\AdvertisingPlatformInterest;
use App\Cpm;
use App\AdvertisingPlatformCallToActionType;
use App\AdvertisingPlatformTransaction;

class AdvertisingPlatformPolicy extends CustomizedPolicy
{

    public function addAdvertisingPlatformInterest(User $user, AdvertisingPlatform $model)
    {
        return $this->policy->can('addAdvertisingPlatformInterest', $model);
    }

    public function addAdvertisingPlatformCallToActionType(User $user, AdvertisingPlatform $model)
    {
        return $this->policy->can('addAdvertisingPlatformCallToActionType', $model);
    }

    public function addAdvertisingPlatformTransaction(User $user, AdvertisingPlatform $model)
    {
        return $this->policy->can('addAdvertisingPlatformTransaction', $model);
    }

}

