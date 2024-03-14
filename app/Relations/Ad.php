<?php

namespace App\Relations;

trait Ad
{

    public function job()
    {
        return $this->belongsTo('App\Job');
    }

    public function advertisingPlatformInterest()
    {
        return $this->belongsTo('App\AdvertisingPlatformInterest');
    }

    public function creative()
    {
        return $this->belongsTo('App\Creative');
    }

    public function applications()
    {
        return $this->hasMany('App\Application');
    }

}
