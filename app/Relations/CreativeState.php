<?php

namespace App\Relations;

trait CreativeState
{

    public function creatives()
    {
        return $this->hasMany('App\Creative');
    }

}
