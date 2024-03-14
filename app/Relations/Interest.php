<?php

namespace App\Relations;

trait Interest
{

    public function functionalAreas()
    {
        return $this->belongsToMany('App\FunctionalArea');
    }

    public function advertisingPlatformInterests()
    {
        return $this->hasMany('App\AdvertisingPlatformInterest');
    }

}
