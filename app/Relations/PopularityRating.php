<?php

namespace App\Relations;

trait PopularityRating
{

    public function companies()
    {
        return $this->hasMany('App\Company');
    }

}
