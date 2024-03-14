<?php

namespace App\Relations;

trait Experience
{

    public function jobs()
    {
        return $this->morphedByMany('App\Job', 'experience_pivot');
    }

    public function profiles()
    {
        return $this->morphedByMany('App\Profile', 'experience_pivot');
    }

}
