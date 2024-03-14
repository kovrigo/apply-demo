<?php

namespace App\Relations;

trait ProjectTaskLink
{

    public function source()
    {
        return $this->belongsTo('App\ProjectTask');
    }

    public function target()
    {
        return $this->belongsTo('App\ProjectTask');
    }    

}
