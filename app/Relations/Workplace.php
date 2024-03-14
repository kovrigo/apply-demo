<?php

namespace App\Relations;

trait Workplace
{

    public function addressable()
    {
        return $this->morphTo()->withTrashed();
    }

    public function workplaceType()
    {
        return $this->belongsTo('App\WorkplaceType');
    }

    public function jobs()
    {
        return $this->hasMany('App\Job');
    }

}
