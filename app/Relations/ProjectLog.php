<?php

namespace App\Relations;

trait ProjectLog
{

    public function projectState()
    {
        return $this->belongsTo('App\ProjectState');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }    

    public function state()
    {
        return $this->projectState();
    }

}

