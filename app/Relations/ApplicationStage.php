<?php

namespace App\Relations;

trait ApplicationStage
{

    public function applications()
    {
        return $this->hasMany('App\Application');
    }

    public function applicationStageType()
    {
        return $this->belongsTo('App\ApplicationStageType');
    }

}
