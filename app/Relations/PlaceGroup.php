<?php

namespace App\Relations;

trait PlaceGroup
{

    public function placeGroupAddresses()
    {
        return $this->hasMany('App\PlaceGroupAddress');
    }

    public function workplaces()
    {
        return $this->morphMany('App\Workplace', 'addressable');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function contacts()
    {
        return $this->morphMany('App\Contact', 'contactable');
    }

}
