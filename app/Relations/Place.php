<?php

namespace App\Relations;

trait Place
{

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
