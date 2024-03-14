<?php

namespace App\Relations;

trait Benefit
{

    public function jobs()
    {
        return $this->belongsToMany('App\Job');
    }

}
