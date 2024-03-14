<?php

namespace App\Relations;

trait CallToActionType
{

    public function creatives()
    {
        return $this->hasMany('App\Creative');
    }

    public function advertisingPlatformCallToActionTypes()
    {
        return $this->hasMany('App\AdvertisingPlatformCallToActionType');
    }

}
