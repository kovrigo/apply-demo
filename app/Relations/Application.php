<?php

namespace App\Relations;

trait Application
{

    public function job()
    {
        return $this->belongsTo('App\Job');
    }

    public function ad()
    {
        return $this->belongsTo('App\Ad');
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function applicationStage()
    {
        return $this->belongsTo('App\ApplicationStage');
    }

    public function applicationState()
    {
        return $this->belongsTo('App\ApplicationState');
    }

    public function applicationLogs()
    {
        return $this->hasMany('App\ApplicationLog');
    }    

}

