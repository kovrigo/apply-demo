<?php

namespace App\Relations;

trait ApplicationStageType
{

    public function applicationStages()
    {
        return $this->hasMany('App\ApplicationStage');
    }

}
