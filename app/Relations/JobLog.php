<?php

namespace App\Relations;

trait JobLog
{

    public function jobState()
    {
        return $this->belongsTo('App\JobState');
    }

    public function job()
    {
        return $this->belongsTo('App\Job');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }    

    public function state()
    {
        return $this->jobState();
    }

}
