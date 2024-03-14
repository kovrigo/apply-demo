<?php

namespace App\Relations;

trait CompensationUnit
{

    public function compensations()
    {
        return $this->hasMany('App\Compensation');
    }

}
