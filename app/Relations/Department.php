<?php

namespace App\Relations;

trait Department
{

    public function users()
    {
        return $this->hasMany('App\User');
    }    

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function projects()
    {
        return $this->morphMany('App\Project', 'customer');
    }    

}

