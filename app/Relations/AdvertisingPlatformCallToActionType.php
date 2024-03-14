<?php

namespace App\Relations;

trait AdvertisingPlatformCallToActionType
{

    public function callToActionType()
    {
        return $this->belongsTo('App\CallToActionType');
    }

    public function advertisingPlatform()
    {
        return $this->belongsTo('App\AdvertisingPlatform');
    }

}
