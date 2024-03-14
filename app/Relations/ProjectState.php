<?php

namespace App\Relations;

trait ProjectState
{

    public function projects()
    {
        return $this->hasMany('App\Project');
    }

}

