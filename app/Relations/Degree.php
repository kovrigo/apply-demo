<?php

namespace App\Relations;

trait Degree
{

    public function jobs()
    {
        return $this->morphedByMany('App\Job', 'degree_pivot');
    }

    public function profiles()
    {
        return $this->morphedByMany('App\Profile', 'degree_pivot');
    }

}
