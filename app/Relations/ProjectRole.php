<?php

namespace App\Relations;

trait ProjectRole
{

    public function users()
    {
        return $this->hasMany('App\User');
    }    

    public function projectRates()
    {
        return $this->hasMany('App\ProjectRate');
    }    

    public function projectTasks()
    {
        return $this->morphMany('App\ProjectTask', 'assignee');
    }

}

