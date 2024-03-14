<?php

namespace App\Relations;

trait Trigger
{

    public function creatives()
    {
        return $this->hasMany('App\Creative');
    }

}
