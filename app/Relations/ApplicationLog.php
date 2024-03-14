<?php

namespace App\Relations;

trait ApplicationLog
{

    public function applicationStage()
    {
        return $this->belongsTo('App\ApplicationStage');
    }

    public function applicationState()
    {
        return $this->belongsTo('App\ApplicationState');
    }

    public function application()
    {
        return $this->belongsTo('App\Application');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function state()
    {
        return $this->applicationStage();
    }

}
