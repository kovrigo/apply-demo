<?php

namespace App\Relations;

trait WorkplaceType
{

    public function workplaces()
    {
        return $this->hasMany('App\Workplace');
    }

}
