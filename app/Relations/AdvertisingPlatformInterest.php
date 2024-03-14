<?php

namespace App\Relations;

trait AdvertisingPlatformInterest
{

    public function interest()
    {
        return $this->belongsTo('App\Interest');
    }

    public function advertisingPlatform()
    {
        return $this->belongsTo('App\AdvertisingPlatform');
    }

    public function ads()
    {
        return $this->hasMany('App\Ad');
    }

}
