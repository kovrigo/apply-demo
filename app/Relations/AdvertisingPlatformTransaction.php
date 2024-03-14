<?php

namespace App\Relations;

trait AdvertisingPlatformTransaction
{

    public function advertisingPlatform()
    {
        return $this->belongsTo('App\AdvertisingPlatform');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

}
