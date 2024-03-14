<?php

namespace App\Relations;

trait Project
{

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function projects()
    {
        return $this->hasMany('App\Project', 'project_id');
    }    

    public function customer()
    {
        return $this->morphTo()->withTrashed();
    }    

    public function projectRates()
    {
        return $this->hasMany('App\ProjectRate');
    }    

    public function projectTasks()
    {
        return $this->morphMany('App\ProjectTask', 'parent');
    }    

    public function projectTransactions()
    {
        return $this->morphMany('App\ProjectTransaction', 'owner');
    }    

}

