<?php

namespace App\Relations;

trait CustomWorkplaceType
{

    public function workplaceType()
    {
        return $this->belongsTo('App\WorkplaceType');
    }

}
