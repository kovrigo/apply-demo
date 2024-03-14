<?php

namespace App\Relations;

trait JobState
{

    public function jobs()
    {
        return $this->hasMany('App\Job');
    }

}
