<?php

namespace App\Relations;

trait Organization
{

    public function projects()
    {
        return $this->morphMany('App\Project', 'customer');
    }    

    public function projectTasks()
    {
        return $this->morphMany('App\ProjectTask', 'assignee');
    }

}

