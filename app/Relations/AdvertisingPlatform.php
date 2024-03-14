<?php

namespace App\Relations;

trait AdvertisingPlatform
{

    public function advertisingPlatformInterests()
    {
        return $this->hasMany('App\AdvertisingPlatformInterest');
    }

    public function advertisingPlatformCallToActionTypes()
    {
        return $this->hasMany('App\AdvertisingPlatformCallToActionType');
    }

    public function advertisingPlatformTransactions()
    {
        return $this->hasMany('App\AdvertisingPlatformTransaction');
    }

}
