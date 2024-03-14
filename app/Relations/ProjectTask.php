<?php

namespace App\Relations;

trait ProjectTask
{

    public function assignee()
    {
        return $this->morphTo()->withTrashed();
    }

    public function parent()
    {
        return $this->morphTo()->withTrashed();
    }    

    public function projectTasks()
    {
        return $this->morphMany('App\ProjectTask', 'parent');
    }    

    public function projectTransactions()
    {
        return $this->morphMany('App\ProjectTransaction', 'owner');
    }    

    public function projectTask()
    {
        return $this->belongsTo('App\ProjectTask');
    }

}

