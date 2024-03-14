<?php

namespace App\Relations;

trait Employment
{

    public function jobs()
    {
        return $this->morphedByMany('App\Job', 'employment_pivot');
    }

    public function profiles()
    {
        return $this->morphedByMany('App\Profile', 'employment_pivot');
    }

    public function pages()
    {
        return $this->morphedByMany('App\Page', 'employment_pivot');
    }

}
