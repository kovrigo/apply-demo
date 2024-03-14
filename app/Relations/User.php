<?php

namespace App\Relations;

trait User
{

    public function userProfile()
    {
        return $this->belongsTo('App\UserProfile');
    }

    public function userProfiles()
    {
        return $this->belongsToMany('App\UserProfile')
            ->using('App\UserUserProfile');
    }

    public function tenant()
    {
        return $this->belongsTo('App\Tenant');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function departments()
    {
        return $this->hasMany('App\Department');
    }    

    public function projectRole()
    {
        return $this->belongsTo('App\ProjectRole');
    }

    public function projectTasks()
    {
        return $this->morphMany('App\ProjectTask', 'assignee');
    }    

}

