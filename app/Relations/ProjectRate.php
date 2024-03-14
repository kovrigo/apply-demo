<?php

namespace App\Relations;

trait ProjectRate
{

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function projectRole()
    {
        return $this->belongsTo('App\ProjectRole');
    }

}

