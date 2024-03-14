<?php

namespace App\Relations;

trait Industry
{

    public function companies()
    {
        return $this->hasMany('App\Company');
    }

}
