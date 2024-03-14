<?php

namespace App\Relations;

trait City
{

    public function jobs()
    {
        return $this->belongsToMany('App\Job');
    }    

}

