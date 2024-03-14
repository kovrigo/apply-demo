<?php

namespace App\Relations;

trait CompanySize
{

    public function companies()
    {
        return $this->hasMany('App\Company');
    }

}
