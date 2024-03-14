<?php

namespace App\Relations;

trait CompensationType
{

    public function compensations()
    {
        return $this->hasMany('App\Compensation');
    }

}
