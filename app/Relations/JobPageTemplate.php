<?php

namespace App\Relations;

trait JobPageTemplate
{

    public function jobs()
    {
        return $this->hasMany('App\Job');
    }    

}

